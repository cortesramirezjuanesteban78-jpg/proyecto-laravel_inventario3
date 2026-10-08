<?php
namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller {

    public function index(Request $request) {
        abort_if(auth()->user()->rol !== 'administrador', 403);
        $q   = $request->get('q');
        $rol = $request->get('rol');

        $usuarios = Usuario::when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nombres', 'like', "%$q%")
                        ->orWhere('apellidos', 'like', "%$q%")
                        ->orWhere('email', 'like', "%$q%");
                });
            })
            ->when($rol, function ($query) use ($rol) {
                $query->where('rol', $rol);
            })
            ->orderBy('id_usuario', 'desc')
            ->paginate(15)
            ->withQueryString();

        $todos     = Usuario::all();
        $total     = $todos->count();
        $admins    = $todos->where('rol', 'administrador')->count();
        $empleados = $todos->where('rol', 'empleado')->count();
        $clientes  = $todos->where('rol', 'cliente')->count();

        return view('usuarios.index', compact('usuarios', 'total', 'admins', 'empleados', 'clientes', 'q', 'rol'));
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
        $rolMap = ['administrador' => 1, 'empleado' => 2, 'cliente' => 3];
        $usuario = Usuario::create([
            'id_rol'        => $rolMap[$data['rol']] ?? 3,
            'nombres'       => $data['nombres'],
            'apellidos'     => $data['apellidos'],
            'email'         => $data['email'],
            'telefono'      => $data['telefono'] ?? null,
            'rol'           => $data['rol'],
            'password_hash' => Hash::make($data['password']),
            'estado'        => 1,
        ]);

        if ($data['rol'] === 'cliente') {
            \App\Models\Cliente::updateOrCreate(
                ['correo' => $usuario->email],
                [
                    'nombre'    => trim($usuario->nombres . ' ' . $usuario->apellidos),
                    'telefono'  => $usuario->telefono,
                    'documento' => 'CLI-' . str_pad($usuario->id_usuario, 4, '0', STR_PAD_LEFT),
                ]
            );
        }

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

        if ($usuario->id_usuario === auth()->id() && $data['rol'] !== 'administrador') {
            return back()->with('error', 'No puedes revocar tu propio rol de administrador.');
        }

        $rolMap = ['administrador' => 1, 'empleado' => 2, 'cliente' => 3];
        $update = [
            'id_rol'    => $rolMap[$data['rol']] ?? 3,
            'nombres'   => $data['nombres'],
            'apellidos' => $data['apellidos'],
            'email'     => $data['email'],
            'telefono'  => $data['telefono'] ?? null,
            'rol'       => $data['rol'],
        ];
        if (!empty($data['password'])) {
            $update['password_hash'] = Hash::make($data['password']);
        }
        $usuario->update($update);

        if ($data['rol'] === 'cliente') {
            \App\Models\Cliente::updateOrCreate(
                ['correo' => $usuario->email],
                [
                    'nombre'    => trim($usuario->nombres . ' ' . $usuario->apellidos),
                    'telefono'  => $usuario->telefono,
                    'documento' => 'CLI-' . str_pad($usuario->id_usuario, 4, '0', STR_PAD_LEFT),
                ]
            );
        }

        return back()->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Permite al Administrador decidir y cambiar el rol de un usuario
     * (por ejemplo, promover un cliente a empleado o revertirlo).
     */
    public function cambiarRol(Request $request, $id) {
        abort_if(auth()->user()->rol !== 'administrador', 403);
        $usuario = Usuario::findOrFail($id);

        if ($usuario->id_usuario === auth()->id()) {
            return back()->with('error', 'No puedes cambiar tu propio rol de administrador.');
        }

        $request->validate([
            'rol' => 'required|in:administrador,empleado,cliente',
        ], [
            'rol.required' => 'El rol es obligatorio.',
            'rol.in'       => 'El rol seleccionado no es válido.',
        ]);

        $rolMap = ['administrador' => 1, 'empleado' => 2, 'cliente' => 3];
        $nuevoRol = $request->rol;

        $usuario->update([
            'rol'    => $nuevoRol,
            'id_rol' => $rolMap[$nuevoRol] ?? 3,
        ]);

        if ($nuevoRol === 'cliente') {
            \App\Models\Cliente::updateOrCreate(
                ['correo' => $usuario->email],
                [
                    'nombre'    => trim($usuario->nombres . ' ' . $usuario->apellidos),
                    'telefono'  => $usuario->telefono,
                    'documento' => 'CLI-' . str_pad($usuario->id_usuario, 4, '0', STR_PAD_LEFT),
                ]
            );
        }

        $nombreCompleto = trim($usuario->nombres . ' ' . $usuario->apellidos);
        $etiqueta = ucfirst($nuevoRol);

        return back()->with('success', "Se actualizó el rol de {$nombreCompleto} a '{$etiqueta}' exitosamente.");
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
        if ($usuario->id_usuario === auth()->id()) {
            return back()->with('error', 'No puedes desactivar tu propia cuenta de administrador.');
        }
        $usuario->update(['estado' => $usuario->estado ? 0 : 1]);
        return back()->with('success', 'Estado actualizado.');
    }
}
