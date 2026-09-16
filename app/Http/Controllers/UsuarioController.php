<?php
namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller {

    public function index(Request $request) {
        abort_if(auth()->user()->rol !== 'administrador', 403);
        $q = $request->get('q');
        $usuarios = Usuario::when($q, fn($query) => $query->where('nombres','like',"%$q%")->orWhere('apellidos','like',"%$q%")->orWhere('email','like',"%$q%"))
            ->orderBy('id_usuario')->paginate(15)->withQueryString();

        $todos = Usuario::all();
        $total     = $todos->count();
        $admins    = $todos->where('rol','administrador')->count();
        $empleados = $todos->where('rol','empleado')->count();
        $clientes  = $todos->where('rol','cliente')->count();

        return view('usuarios.index', compact('usuarios','total','admins','empleados','clientes','q'));
    }

    public function store(Request $request) {
        abort_if(auth()->user()->rol !== 'administrador', 403);
        $data = $request->validate([
            'nombres'   => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'email'     => 'required|email|unique:usuarios,email',
            'telefono'  => 'nullable|string|max:30',
            'rol'       => 'required|in:administrador,empleado,cliente',
            'password'  => 'required|min:6',
        ]);
        $rolMap = ['administrador'=>1,'empleado'=>2,'cliente'=>3];
        Usuario::create([
            'id_rol'        => $rolMap[$data['rol']] ?? 3,
            'nombres'       => $data['nombres'],
            'apellidos'     => $data['apellidos'],
            'email'         => $data['email'],
            'telefono'      => $data['telefono'] ?? null,
            'rol'           => $data['rol'],
            'password_hash' => Hash::make($data['password']),
            'estado'        => 1,
        ]);
        return back()->with('success', 'Usuario creado correctamente.');
    }

    public function update(Request $request, $id) {
        abort_if(auth()->user()->rol !== 'administrador', 403);
        $usuario = Usuario::findOrFail($id);
        $data = $request->validate([
            'nombres'   => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'email'     => "required|email|unique:usuarios,email,$id,id_usuario",
            'telefono'  => 'nullable|string|max:30',
            'rol'       => 'required|in:administrador,empleado,cliente',
            'password'  => 'nullable|min:6',
        ]);
        $rolMap = ['administrador'=>1,'empleado'=>2,'cliente'=>3];
        $update = [
            'id_rol'   => $rolMap[$data['rol']] ?? 3,
            'nombres'  => $data['nombres'],
            'apellidos'=> $data['apellidos'],
            'email'    => $data['email'],
            'telefono' => $data['telefono'] ?? null,
            'rol'      => $data['rol'],
        ];
        if (!empty($data['password'])) $update['password_hash'] = Hash::make($data['password']);
        $usuario->update($update);
        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy($id) {
        abort_if(auth()->user()->rol !== 'administrador', 403);
        $usuario = Usuario::findOrFail($id);
        if ($usuario->id_usuario === auth()->id()) return back()->with('error', 'No puedes eliminarte a ti mismo.');
        $usuario->delete();
        return back()->with('success', 'Usuario eliminado.');
    }

    public function toggleEstado($id) {
        abort_if(auth()->user()->rol !== 'administrador', 403);
        $usuario = Usuario::findOrFail($id);
        $usuario->update(['estado' => $usuario->estado ? 0 : 1]);
        return back()->with('success', 'Estado actualizado.');
    }
}
