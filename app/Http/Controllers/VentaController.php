<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Barryvdh\DomPDF\Facade\Pdf;

class VentaController extends Controller
{
    public function index()
    {
        abort_if(auth()->user()->rol !== 'administrador', 403);
        $ventas = Venta::with(['usuario', 'cliente'])
            ->orderBy('id_venta', 'desc')
            ->paginate(15);

        $totalVentas   = Venta::where('estado', '!=', 'cancelada')->count();
        $ventasHoy     = Venta::whereDate('fecha_venta', today())->where('estado', '!=', 'cancelada')->count();
        $ingresosHoy   = (float) Venta::whereDate('fecha_venta', today())->where('estado', '!=', 'cancelada')->sum('total');
        $totalIngresos = (float) Venta::where('estado', '!=', 'cancelada')->sum('total');
        $totalCompras  = (float) (DB::table('compras')->where('estado', '!=', 'cancelada')->sum('total') ?? 0);
        $ganancias     = $totalIngresos - $totalCompras;
        $margenGanancia = $totalIngresos > 0 ? round(($ganancias / $totalIngresos) * 100, 1) : 0;

        // Sincronizar automáticamente todos los usuarios con rol 'cliente' en la tabla de clientes
        $usuariosClientes = Usuario::where('rol', 'cliente')->get();
        foreach ($usuariosClientes as $uc) {
            \App\Models\Cliente::updateOrCreate(
                ['correo' => $uc->email],
                [
                    'nombre'    => trim($uc->nombres . ' ' . $uc->apellidos),
                    'telefono'  => $uc->telefono,
                    'documento' => 'CLI-' . str_pad($uc->id_usuario, 4, '0', STR_PAD_LEFT),
                ]
            );
        }

        // Datos para el modal de nueva venta (artículos disponibles y clientes del sistema)
        $productos = Producto::where('estado', 1)->where('stock_actual', '>', 0)->orderBy('nombre')->get();
        $correosClientes = $usuariosClientes->pluck('email')->filter();
        $clientes  = \App\Models\Cliente::whereIn('correo', $correosClientes)
            ->orWhereNull('correo')
            ->orderBy('nombre')
            ->get();

        return view('ventas.index', compact(
            'ventas', 'totalVentas', 'ventasHoy', 'ingresosHoy', 'totalIngresos',
            'ganancias', 'margenGanancia',
            'productos', 'clientes'
        ));
    }

    public function create()
    {
        return redirect()->route('ventas.index');
    }

    public function store(Request $r)
    {
        abort_if(auth()->user()->rol !== 'administrador', 403);
        $r->validate([
            'metodo_pago'        => 'required|in:efectivo,tarjeta,transferencia',
            'estado'             => 'required|in:pendiente,completada,cancelada',
            'productos'          => 'required|array|min:1',
            'productos.*'        => 'exists:productos,id_producto',
            'cantidades'         => 'required|array|min:1',
            'cantidades.*'       => 'integer|min:1',
            'id_cliente'         => 'nullable|exists:clientes,id_cliente',
        ]);

        DB::beginTransaction();

        try {
            $total     = 0;
            $detalles  = [];

            foreach ($r->productos as $idx => $idProducto) {
                $producto = Producto::findOrFail($idProducto);
                $cantidad = (int)($r->cantidades[$idx] ?? 1);
                $subtotal = $producto->precio * $cantidad;
                $total   += $subtotal;

                $detalles[] = [
                    'id_producto' => $idProducto,
                    'cantidad'    => $cantidad,
                    'precio'      => $producto->precio,
                    'subtotal'    => $subtotal,
                    'producto'    => $producto,
                ];
            }

            $venta = Venta::create([
                'id_usuario'  => Auth::user()->id_usuario,
                'id_cliente'  => $r->id_cliente ?: null,
                'fecha_venta' => now()->toDateTimeString(),
                'metodo_pago' => $r->metodo_pago,
                'estado'      => $r->estado,
                'total'       => $total,
            ]);

            $productosConAlerta = [];
            foreach ($detalles as $detalle) {
                DetalleVenta::create([
                    'id_venta'    => $venta->id_venta,
                    'id_producto' => $detalle['id_producto'],
                    'cantidad'    => $detalle['cantidad'],
                    'precio'      => $detalle['precio'],
                    'subtotal'    => $detalle['subtotal'],
                ]);

                $producto = $detalle['producto'];
                $producto->stock_actual -= $detalle['cantidad'];
                $producto->save();

                if ($producto->stock_actual <= $producto->stock_minimo) {
                    $nivel = $producto->stock_actual == 0 ? '¡AGOTADO!' : 'quedan ' . $producto->stock_actual . ' unid. (mín. ' . $producto->stock_minimo . ')';
                    $productosConAlerta[] = '<strong>' . e($producto->nombre) . '</strong> (' . $nivel . ')';
                }

                MovimientoInventario::create([
                    'id_producto'      => $detalle['id_producto'],
                    'id_usuario'       => Auth::user()->id_usuario,
                    'tipo_movimiento'  => 'salida',
                    'cantidad'         => $detalle['cantidad'],
                    'fecha_movimiento' => now()->toDateTimeString(),
                    'observacion'      => 'Venta #' . $venta->id_venta,
                ]);
            }

            DB::commit();

            // Si el cajero seleccionó generar/abrir factura inmediatamente
            if ($r->boolean('imprimir_factura', true)) {
                $response = redirect()->route('ventas.factura', $venta->id_venta)
                    ->with('success', '¡Venta #' . $venta->id_venta . ' registrada correctamente! Total: $' . number_format($total, 2));
            } else {
                $response = redirect()->route('ventas.index')
                    ->with('success', 'Venta #' . $venta->id_venta . ' registrada correctamente. Total: $' . number_format($total, 2))
                    ->with('venta_creada_id', $venta->id_venta);
            }

            if (!empty($productosConAlerta)) {
                $response->with('warning', '⚠️ <strong>¡Alerta de Stock Crítico!</strong> Tras esta venta, se requiere reabastecimiento para: ' . implode(', ', $productosConAlerta));
            }

            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al registrar la venta: ' . $e->getMessage());
        }
    }

    /**
     * Muestra la factura de venta en formato web interactivo e imprimible.
     */
    public function factura($id)
    {
        $venta = Venta::with(['usuario', 'cliente', 'detalles.producto.categoria'])
            ->findOrFail($id);

        return view('ventas.factura', compact('venta'));
    }

    /**
     * Genera y descarga la factura en formato PDF (DomPDF).
     */
    public function facturaPdf($id)
    {
        $venta = Venta::with(['usuario', 'cliente', 'detalles.producto.categoria'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('ventas.factura_pdf', compact('venta'))
            ->setPaper('a4', 'portrait')
            ->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download("factura_superfresco_{$venta->id_venta}.pdf");
    }

    public function destroy($id)
    {
        abort_if(auth()->user()->rol !== 'administrador', 403);
        Venta::findOrFail($id)->delete();

        return redirect()->route('ventas.index')
            ->with('success', 'Venta eliminada.');
    }
}
