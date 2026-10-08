<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $rol = Auth::user()->rol;

        if ($rol === 'cliente') {
            return redirect()->route('productos.index');
        }

        if ($rol === 'administrador') {
            $totalVentas      = (float) DB::table('ventas')->where('estado', '!=', 'cancelada')->sum('total');
            $totalCompras     = (float) (DB::table('compras')->where('estado', '!=', 'cancelada')->sum('total') ?? 0);
            $gananciasTotales = $totalVentas - $totalCompras;
            $ventasHoy        = (float) DB::table('ventas')->whereDate('fecha_venta', today())->where('estado', '!=', 'cancelada')->sum('total');
            $valorInventario  = (float) (DB::table('productos')->where('estado', 1)->selectRaw('SUM(stock_actual * precio) as val')->value('val') ?? 0);

            $stats = [
                'usuarios'         => DB::table('usuarios')->where('estado', 1)->count(),
                'productos'        => DB::table('productos')->where('estado', 1)->count(),
                'categorias'       => DB::table('categorias')->count(),
                'proveedores'      => DB::table('proveedores')->where('estado', 1)->count(),
                'totalVentas'      => $totalVentas,
                'totalCompras'     => $totalCompras,
                'gananciasTotales' => $gananciasTotales,
                'ventasHoy'        => $ventasHoy,
                'valorInventario'  => $valorInventario,
            ];
            $stockBajoCount = DB::table('productos')
                ->where('estado', 1)
                ->whereColumn('stock_actual', '<=', 'stock_minimo')
                ->count();
            $productosStockBajo = \App\Models\Producto::with('categoria')
                ->where('estado', 1)
                ->whereColumn('stock_actual', '<=', 'stock_minimo')
                ->orderBy('stock_actual')
                ->limit(6)
                ->get();

            return view('dashboard', compact('stats', 'stockBajoCount', 'productosStockBajo'));
        }

        // ── Panel Empleado ──────────────────────────────────────
        $totalProductos  = DB::table('productos')->where('estado', 1)->count();
        $productosActivos = $totalProductos;
        $stockBajo       = DB::table('productos')
                             ->where('estado', 1)
                             ->whereColumn('stock_actual', '<=', 'stock_minimo')
                             ->where('stock_actual', '>', 0)
                             ->count();
        $valorInventario = DB::table('productos')
                             ->where('estado', 1)
                             ->selectRaw('SUM(stock_actual * precio) as valor')
                             ->value('valor') ?? 0;

        return view('dashboard-empleado', compact(
            'totalProductos', 'productosActivos', 'stockBajo', 'valorInventario'
        ));
    }
}
