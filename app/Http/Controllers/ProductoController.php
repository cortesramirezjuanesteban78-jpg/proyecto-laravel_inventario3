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
        $isCliente = auth()->check() && auth()->user()->rol === 'cliente';
        $query = Producto::with(['categoria', 'proveedor']);

        // Si es cliente, solo ve productos activos
        if ($isCliente) {
            $query->where('estado', 1);
        }

        if ($r->filled('search')) {
            $search = $r->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('codigo', 'like', "%{$search}%");
            });
        }

        if ($r->filled('estado') && !$isCliente) {
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
            'productos', 'total', 'activos', 'stockBajo', 'agotados', 'categorias', 'proveedores', 'isCliente'
        ));
    }

    public function create()
    {
        abort_if(auth()->user()->rol === 'cliente', 403, 'Los clientes solo pueden visualizar los productos.');
        $categorias = Categoria::orderBy('nombre')->get();
        $proveedores = Proveedor::where('estado', 1)->orderBy('nombre')->get();

        return view('productos.create', compact('categorias', 'proveedores'));
    }

    public function store(Request $r)
    {
        abort_if(auth()->user()->rol === 'cliente', 403, 'Los clientes solo pueden visualizar los productos.');
        $r->validate([
            'nombre'         => 'required|string|max:150',
            'codigo'         => 'required|string|max:50|unique:productos,codigo',
            'descripcion'    => 'nullable|string',
            'precio'         => 'required|numeric|min:0',
            'stock_actual'   => 'required|integer|min:0',
            'stock_minimo'   => 'required|integer|min:0',
            'id_categoria'   => 'required|exists:categorias,id_categoria',
            'id_proveedor'   => 'nullable|exists:proveedores,id_proveedor',
            'estado'         => 'required|in:0,1',
            'imagen_archivo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'imagen_url'     => 'nullable|string|max:500',
        ]);

        $data = $r->only([
            'nombre', 'codigo', 'descripcion', 'precio',
            'stock_actual', 'stock_minimo', 'id_categoria',
            'id_proveedor', 'estado',
        ]);

        if ($r->hasFile('imagen_archivo') && $r->file('imagen_archivo')->isValid()) {
            $file = $r->file('imagen_archivo');
            $fileName = 'prod_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/productos'), $fileName);
            $data['imagen'] = 'uploads/productos/' . $fileName;
        } elseif ($r->filled('imagen_url')) {
            $data['imagen'] = trim($r->imagen_url);
        }

        Producto::create($data);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado correctamente.');
    }

    public function edit($id)
    {
        abort_if(auth()->user()->rol === 'cliente', 403, 'Los clientes solo pueden visualizar los productos.');
        $producto    = Producto::findOrFail($id);
        $categorias  = Categoria::orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('nombre')->get();

        return view('productos.edit', compact('producto', 'categorias', 'proveedores'));
    }

    public function update(Request $r, $id)
    {
        abort_if(auth()->user()->rol === 'cliente', 403, 'Los clientes solo pueden visualizar los productos.');
        $producto = Producto::findOrFail($id);

        $r->validate([
            'nombre'         => 'required|string|max:150',
            'codigo'         => 'required|string|max:50|unique:productos,codigo,' . $id . ',id_producto',
            'descripcion'    => 'nullable|string',
            'precio'         => 'required|numeric|min:0',
            'stock_actual'   => 'required|integer|min:0',
            'stock_minimo'   => 'required|integer|min:0',
            'id_categoria'   => 'required|exists:categorias,id_categoria',
            'id_proveedor'   => 'nullable|exists:proveedores,id_proveedor',
            'estado'         => 'required|in:0,1',
            'imagen_archivo' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'imagen_url'     => 'nullable|string|max:500',
            'eliminar_imagen'=> 'nullable|boolean',
        ]);

        $data = $r->only([
            'nombre', 'codigo', 'descripcion', 'precio',
            'stock_actual', 'stock_minimo', 'id_categoria',
            'id_proveedor', 'estado',
        ]);

        if ($r->boolean('eliminar_imagen')) {
            if ($producto->imagen && file_exists(public_path($producto->imagen))) {
                @unlink(public_path($producto->imagen));
            }
            $data['imagen'] = null;
        } elseif ($r->hasFile('imagen_archivo') && $r->file('imagen_archivo')->isValid()) {
            if ($producto->imagen && file_exists(public_path($producto->imagen))) {
                @unlink(public_path($producto->imagen));
            }
            $file = $r->file('imagen_archivo');
            $fileName = 'prod_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/productos'), $fileName);
            $data['imagen'] = 'uploads/productos/' . $fileName;
        } elseif ($r->filled('imagen_url')) {
            if ($producto->imagen && file_exists(public_path($producto->imagen))) {
                @unlink(public_path($producto->imagen));
            }
            $data['imagen'] = trim($r->imagen_url);
        }

        $producto->update($data);

        $response = redirect()->route('productos.index')
            ->with('success', 'Producto actualizado correctamente.');

        if ($producto->stock_actual <= $producto->stock_minimo) {
            $estado = $producto->stock_actual == 0 
                ? '¡Agotado! (0 unidades)' 
                : 'Stock bajo (' . $producto->stock_actual . ' de ' . $producto->stock_minimo . ' mín.)';
            $response->with('warning', '⚠️ Atención: El producto <strong>' . e($producto->nombre) . '</strong> ha quedado en ' . $estado . '.');
        }

        return $response;
    }

    public function destroy($id)
    {
        abort_if(auth()->user()->rol === 'cliente', 403, 'Los clientes solo pueden visualizar los productos.');
        $producto = Producto::findOrFail($id);
        if ($producto->imagen && file_exists(public_path($producto->imagen))) {
            @unlink(public_path($producto->imagen));
        }
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado.');
    }

    public function toggleEstado($id)
    {
        abort_if(auth()->user()->rol === 'cliente', 403, 'Los clientes solo pueden visualizar los productos.');
        $producto = Producto::findOrFail($id);
        $producto->update(['estado' => $producto->estado ? 0 : 1]);

        return redirect()->back()
            ->with('success', 'Estado del producto actualizado.');
    }
}
