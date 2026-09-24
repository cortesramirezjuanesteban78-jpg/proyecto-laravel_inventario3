<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // ─── LOGIN ────────────────────────────────────────────────────────────────

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'El correo es obligatorio.',
            'email.email'       => 'Ingresa un correo válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
        ]);

        $usuario = Usuario::where('email', $request->email)
                          ->where('estado', 1)
                          ->first();

        if (! $usuario || ! Hash::check($request->password, $usuario->password_hash)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Correo o contraseña incorrectos.']);
        }

        Auth::login($usuario, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }

    // ─── REGISTRO ─────────────────────────────────────────────────────────────

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nombres'   => 'required|string|max:100',
            'apellidos' => 'required|string|max:100',
            'email'     => 'required|email|max:150|unique:usuarios,email',
            'telefono'  => 'nullable|string|max:30',
            'password'  => 'required|min:6|confirmed',
        ], [
            'nombres.required'   => 'El nombre es obligatorio.',
            'apellidos.required' => 'El apellido es obligatorio.',
            'email.required'     => 'El correo es obligatorio.',
            'email.email'        => 'Ingresa un correo válido.',
            'email.unique'       => 'Este correo ya está registrado.',
            'password.required'  => 'La contraseña es obligatoria.',
            'password.min'       => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $rolCliente = \App\Models\Rol::where('nombre', 'cliente')->value('id_rol') ?? 3;

        $usuario = Usuario::create([
            'id_rol'        => $rolCliente,
            'nombres'       => $request->nombres,
            'apellidos'     => $request->apellidos,
            'email'         => $request->email,
            'password_hash' => Hash::make($request->password),
            'telefono'      => $request->telefono,
            'rol'           => 'cliente',
            'estado'        => 1,
        ]);

        Auth::login($usuario);
        $request->session()->regenerate();

        return redirect()->route('dashboard')->with('success', '¡Bienvenido, ' . $usuario->nombres . '!');
    }

    // ─── LOGOUT ───────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
