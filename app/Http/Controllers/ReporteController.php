<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index(Request $r)
    {
        $year = $r->get('year', now()->year);

        // ── Stat cards ──────────────────────────────────────────
        $totalUsuarios  = Usuario::count();
        $totalProductos = Producto::count();
        $totalVentas    = DB::table('ventas')
            ->whereYear('fecha_venta', $year)
            ->sum('total');
        $totalCompras   = DB::table('compras')
            ->whereYear('fecha_compra', $year)
            ->sum('total') ?? 0;

        // ── Ventas por mes ──────────────────────────────────────
        $ventasPorMes = DB::table('ventas')
            ->selectRaw('MONTH(fecha_venta) as mes, SUM(total) as total, COUNT(*) as cantidad')
            ->whereYear('fecha_venta', $year)
            ->groupByRaw('MONTH(fecha_venta)')
            ->orderBy('mes')
            ->get()
            ->keyBy('mes');

        // Fill all 12 months
        $meses = [];
        $mesesNombres = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        $maxVenta = 0;
        for ($m = 1; $m <= 12; $m++) {
            $val = $ventasPorMes->get($m);
            $total = $val ? (float)$val->total : 0;
            if ($total > $maxVenta) $maxVenta = $total;
            $meses[$m] = [
                'nombre'   => $mesesNombres[$m - 1],
                'total'    => $total,
                'cantidad' => $val ? $val->cantidad : 0,
            ];
        }

        // ── Top 10 productos más vendidos ───────────────────────
        $topProductos = DB::table('detalle_ventas')
            ->join('productos', 'detalle_ventas.id_producto', '=', 'productos.id_producto')
            ->join('ventas', 'detalle_ventas.id_venta', '=', 'ventas.id_venta')
            ->whereYear('ventas.fecha_venta', $year)
            ->selectRaw('productos.nombre, SUM(detalle_ventas.cantidad) as total_vendido, SUM(detalle_ventas.subtotal) as ingresos')
            ->groupBy('detalle_ventas.id_producto', 'productos.nombre')
            ->orderByDesc('total_vendido')
            ->limit(10)
            ->get();

        // ── Productos con stock bajo ────────────────────────────
        $productosStockBajo = Producto::with('categoria')
            ->where('estado', 1)
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->orderBy('stock_actual')
            ->get();

        // ── Compras por proveedor ───────────────────────────────
        $comprasPorProveedor = DB::table('compras')
            ->join('proveedores', 'compras.id_proveedor', '=', 'proveedores.id_proveedor')
            ->whereYear('fecha_compra', $year)
            ->selectRaw('proveedores.nombre, COUNT(*) as total_compras, SUM(compras.total) as total_gastado')
            ->groupBy('compras.id_proveedor', 'proveedores.nombre')
            ->orderByDesc('total_gastado')
            ->get();

        return view('reportes.index', compact(
            'year', 'totalUsuarios', 'totalProductos', 'totalVentas', 'totalCompras',
            'meses', 'maxVenta', 'topProductos', 'productosStockBajo', 'comprasPorProveedor'
        ));
    }
}
