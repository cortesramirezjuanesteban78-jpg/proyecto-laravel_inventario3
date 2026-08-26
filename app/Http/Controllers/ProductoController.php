<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    public function index(Request $r)
    {
        $query = Producto::with(['categoria', 'proveedor']);

        if ($r->filled('search')) {
            $search = $r->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%");
            });
        }

        if ($r->filled('estado')) {
            $query->where('estado', $r->estado);
        }

        if ($r->filled('categoria')) {
            $query->where('id_categoria', $r->categoria);
        }

        $productos = $query->orderBy('id_producto', 'desc')->paginate(12)->withQueryString();

        $total     = Producto::count();
        $activos   = Producto::where('estado', 1)->count();
        $stockBajo = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')
                              ->where('stock_actual', '>', 0)->count();
        $agotados  = Producto::where('stock_actual', 0)->count();

        $categorias  = Categoria::orderBy('nombre')->get();
        $proveedores = Proveedor::where('estado', 1)->orderBy('nombre')->get();

        return view('productos.index', compact(
            'productos', 'total', 'activos', 'stockBajo', 'agotados', 'categorias', 'proveedores'
        ));
    }

    public function create()
    {
        $categorias = Categoria::orderBy('nombre')->get();
        $proveedores = Proveedor::where('estado', 1)->orderBy('nombre')->get();

        return view('productos.create', compact('categorias', 'proveedores'));
    }

    public function store(Request $r)
    {
        $r->validate([
            'nombre'       => 'required|string|max:150',
            'codigo'       => 'required|string|max:50|unique:productos,codigo',
            'descripcion'  => 'nullable|string',
            'precio'       => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'id_proveedor' => 'nullable|exists:proveedores,id_proveedor',
            'estado'       => 'required|in:0,1',
        ]);

        Producto::create($r->only([
            'nombre', 'codigo', 'descripcion', 'precio',
            'stock_actual', 'stock_minimo', 'id_categoria',
            'id_proveedor', 'estado',
        ]));

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit($id)
    {
        $producto    = Producto::findOrFail($id);
        $categorias  = Categoria::orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre')->get();

        return view('productos.edit', compact('producto', 'categorias', 'proveedores'));
    }

    public function update(Request $r, $id)
    {
        $producto = Producto::findOrFail($id);

        $r->validate([
            'nombre'       => 'required|string|max:150',
            'codigo'       => 'required|string|max:50|unique:productos,codigo,' . $id . ',id_producto',
            'descripcion'  => 'nullable|string',
            'precio'       => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'id_categoria' => 'required|exists:categorias,id_categoria',
            'id_proveedor' => 'nullable|exists:proveedores,id_proveedor',
            'estado'       => 'required|in:0,1',
        ]);

        $producto->update($r->only([
            'nombre', 'codigo', 'descripcion', 'precio',
            'stock_actual', 'stock_minimo', 'id_categoria',
            'id_proveedor', 'estado',
        ]));

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy($id)
    {
        Producto::findOrFail($id)->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado.');
    }

    public function toggleEstado($id)
    {
        $producto = Producto::findOrFail($id);
        $producto->update(['estado' => $producto->estado ? 0 : 1]);

        return redirect()->back()
            ->with('success', 'Estado del producto actualizado.');
    }
}
