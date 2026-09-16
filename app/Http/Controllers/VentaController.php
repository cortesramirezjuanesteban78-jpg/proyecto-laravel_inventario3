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

class VentaController extends Controller
{
    public function index()
    {
        abort_if(auth()->user()->rol !== 'administrador', 403);
        $ventas = Venta::with(['usuario', 'cliente'])
            ->orderBy('id_venta', 'desc')
            ->paginate(15);

        $totalVentas   = Venta::count();
        $ventasHoy     = Venta::whereDate('fecha_venta', today())->count();
        $ingresosHoy   = Venta::whereDate('fecha_venta', today())->sum('total');
        $totalIngresos = Venta::sum('total');

        // Datos para el modal de nueva venta
        $productos = Producto::where('estado', 1)->where('stock_actual', '>', 0)->orderBy('nombre')->get();
        $clientes  = \App\Models\Cliente::orderBy('nombre')->get();

        return view('ventas.index', compact(
            'ventas', 'totalVentas', 'ventasHoy', 'ingresosHoy', 'totalIngresos',
            'productos', 'clientes'
        ));
    }

    public function create()
    {
        $productos = Producto::where('estado', 1)->where('stock_actual', '>', 0)
            ->orderBy('nombre')->get();
        $clientes  = Usuario::where('rol', 'cliente')->orderBy('nombres')->get();

        return view('ventas.create', compact('productos', 'clientes'));
    }

    public function store(Request $r)
    {
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

            return redirect()->route('ventas.index')
                ->with('success', 'Venta registrada correctamente. Total: $' . number_format($total, 2));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al registrar la venta: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        Venta::findOrFail($id)->delete();

        return redirect()->route('ventas.index')
            ->with('success', 'Venta eliminada.');
    }
}
