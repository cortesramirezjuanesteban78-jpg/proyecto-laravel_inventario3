<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    public function index()
    {
        abort_if(auth()->user()->rol !== 'administrador', 403);
        $proveedores = Proveedor::orderBy('id_proveedor', 'desc')->paginate(15);
        $activos     = Proveedor::where('estado', 1)->count();
        $inactivos   = Proveedor::where('estado', 0)->count();
        $total       = Proveedor::count();

        return view('proveedores.index', compact(
            'proveedores', 'activos', 'inactivos', 'total'
        ));
    }

    public function store(Request $r)
    {
        $r->validate([
            'nombre'    => 'required|string|max:150',
            'documento' => 'nullable|string|max:50',
            'telefono'  => 'nullable|string|max:20',
            'correo'    => 'nullable|email|max:100',
            'direccion' => 'nullable|string|max:255',
        ]);

        Proveedor::create([
            'nombre'    => $r->nombre,
            'documento' => $r->documento,
            'telefono'  => $r->telefono,
            'correo'    => $r->correo,
            'direccion' => $r->direccion,
            'estado'    => 1,
        ]);

        return redirect()->back()
            ->with('success', 'Proveedor agregado correctamente.');
    }

    public function update(Request $r, $id)
    {
        $proveedor = Proveedor::findOrFail($id);

        $r->validate([
            'nombre'    => 'required|string|max:150',
            'documento' => 'nullable|string|max:50',
            'telefono'  => 'nullable|string|max:20',
            'correo'    => 'nullable|email|max:100',
            'direccion' => 'nullable|string|max:255',
        ]);

        $proveedor->update($r->only([
            'nombre', 'documento', 'telefono', 'correo', 'direccion',
        ]));

        return redirect()->back()
            ->with('success', 'Proveedor actualizado correctamente.');
    }

    public function destroy($id)
    {
        Proveedor::findOrFail($id)->delete();

        return redirect()->back()
            ->with('success', 'Proveedor eliminado.');
    }

    public function toggleEstado($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $proveedor->update(['estado' => $proveedor->estado ? 0 : 1]);

        return redirect()->back()
            ->with('success', 'Estado del proveedor actualizado.');
    }
}
