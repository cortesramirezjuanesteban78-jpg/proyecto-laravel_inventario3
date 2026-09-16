<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index()
    {
        abort_if(auth()->user()->rol !== 'administrador', 403);
        $categorias = Categoria::orderBy('id_categoria', 'asc')->get();
        $count      = $categorias->count();

        return view('categorias.index', compact('categorias', 'count'));
    }

    public function store(Request $r)
    {
        abort_if(auth()->user()->rol !== 'administrador', 403);
        $r->validate([
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
        ]);

        Categoria::create([
            'nombre'      => $r->nombre,
            'descripcion' => $r->descripcion,
        ]);

        return redirect()->back()
            ->with('success', 'Categoría creada correctamente.');
    }

    public function update(Request $r, $id)
    {
        abort_if(auth()->user()->rol !== 'administrador', 403);
        $categoria = Categoria::findOrFail($id);

        $r->validate([
            'nombre'      => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $categoria->update([
            'nombre'      => $r->nombre,
            'descripcion' => $r->descripcion,
        ]);

        return redirect()->back()
            ->with('success', 'Categoría actualizada correctamente.');
    }

    public function destroy($id)
    {
        abort_if(auth()->user()->rol !== 'administrador', 403);
        Categoria::findOrFail($id)->delete();

        return redirect()->back()
            ->with('success', 'Categoría eliminada.');
    }
}
