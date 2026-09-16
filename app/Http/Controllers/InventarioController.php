<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\MovimientoInventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventarioController extends Controller
{
    public function index(Request $r)
    {
        $query = Producto::with('categoria')->where('estado', 1);

        if ($r->filled('search')) {
            $search = $r->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%");
            });
        }

        $productos = $query->orderBy('nombre')->paginate(15)->withQueryString();

        $productosActivos = Producto::where('estado', 1)->count();
        $totalUnidades    = Producto::where('estado', 1)->sum('stock_actual');
        $stockBajo        = Producto::where('estado', 1)
                                    ->whereColumn('stock_actual', '<=', 'stock_minimo')
                                    ->where('stock_actual', '>', 0)
                                    ->count();
        $valorTotal       = Producto::where('estado', 1)
                                    ->selectRaw('SUM(stock_actual * precio) as valor')
                                    ->value('valor') ?? 0;

        return view('inventario.index', compact(
            'productos', 'productosActivos', 'totalUnidades', 'stockBajo', 'valorTotal'
        ));
    }

    public function stockLive(Request $r)
    {
        $query = Producto::with('categoria')->where('estado', 1);

        if ($r->filled('search')) {
            $search = $r->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%");
            });
        }

        $productos = $query->orderBy('nombre')->paginate(15)->withQueryString();

        $stats = [
            'productosActivos' => Producto::where('estado', 1)->count(),
            'totalUnidades'    => (int) Producto::where('estado', 1)->sum('stock_actual'),
            'stockBajo'        => Producto::where('estado', 1)
                                          ->whereColumn('stock_actual', '<=', 'stock_minimo')
                                          ->where('stock_actual', '>', 0)
                                          ->count(),
            'valorTotal'       => (float) (Producto::where('estado', 1)
                                          ->selectRaw('SUM(stock_actual * precio) as valor')
                                          ->value('valor') ?? 0),
        ];

        $rows = $productos->map(function ($p) {
            if ($p->stock_actual == 0)                          { $level = 'agot'; $label = 'AGOTADO'; }
            elseif ($p->stock_actual <= $p->stock_minimo)       { $level = 'bajo'; $label = 'BAJO'; }
            else                                                 { $level = 'ok';   $label = 'OK'; }

            $pct = $p->stock_minimo > 0
                ? min(100, ($p->stock_actual / ($p->stock_minimo * 2)) * 100)
                : min(100, $p->stock_actual * 5);

            return [
                'id'            => $p->id_producto,
                'nombre'        => $p->nombre,
                'categoria'     => $p->categoria ? $p->categoria->nombre : null,
                'codigo'        => $p->codigo,
                'stock_actual'  => $p->stock_actual,
                'stock_minimo'  => $p->stock_minimo,
                'precio'        => $p->precio,
                'valor_stock'   => $p->stock_actual * $p->precio,
                'level'         => $level,
                'level_label'   => $label,
                'pct'           => round($pct),
            ];
        });

        return response()->json([
            'stats'     => $stats,
            'productos' => $rows,
            'timestamp' => now()->format('H:i:s'),
        ]);
    }

    public function mover(Request $r, $id)
    {
        $producto = Producto::findOrFail($id);

        $r->validate([
            'tipo_movimiento' => 'required|in:entrada,salida,ajuste',
            'cantidad'        => 'required|integer|min:1',
            'observacion'     => 'nullable|string|max:255',
        ]);

        $cantidad = (int) $r->cantidad;

        if ($r->tipo_movimiento === 'entrada') {
            $producto->stock_actual += $cantidad;
        } elseif ($r->tipo_movimiento === 'salida') {
            if ($producto->stock_actual < $cantidad) {
                return redirect()->back()
                    ->with('error', 'No hay suficiente stock para realizar la salida.');
            }
            $producto->stock_actual -= $cantidad;
        } elseif ($r->tipo_movimiento === 'ajuste') {
            $producto->stock_actual = $cantidad;
        }

        $producto->save();

        MovimientoInventario::create([
            'id_producto'     => $producto->id_producto,
            'id_usuario'      => Auth::user()->id_usuario,
            'tipo_movimiento' => $r->tipo_movimiento,
            'cantidad'        => $cantidad,
            'fecha_movimiento'=> now()->toDateTimeString(),
            'observacion'     => $r->observacion,
        ]);

        return redirect()->back()
            ->with('success', 'Movimiento registrado correctamente.');
    }
}
