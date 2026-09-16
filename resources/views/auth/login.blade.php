@extends('layouts.app')
@section('title', 'Iniciar sesión — SuperFresco')

@push('styles')
<style>
    body { background: #f5f7f5; }

    .auth-page {
        min-height: calc(100vh - 66px);
        display: flex; align-items: stretch;
    }

    /* ── Panel visual ── */
    .auth-visual {
        flex: 1;
        background: linear-gradient(145deg, #052e16 0%, #14532d 45%, #16a34a 100%);
        display: flex; flex-direction: column;
        justify-content: center; align-items: flex-start;
        padding: 4rem 3.5rem;
        position: relative; overflow: hidden;
    }
    .auth-visual::before {
        content: ''; position: absolute; top: -120px; right: -120px;
        width: 420px; height: 420px; border-radius: 50%;
        background: rgba(255,255,255,.05);
    }
    .auth-visual::after {
        content: ''; position: absolute; bottom: -80px; left: -80px;
        width: 280px; height: 280px; border-radius: 50%;
        background: rgba(255,255,255,.04);
    }
    .auth-visual .logo { display: flex; align-items: center; gap: .6rem; margin-bottom: 3rem; }
    .auth-visual .logo span { font-size: 1.9rem; }
    .auth-visual .logo .name { font-size: 1.4rem; font-weight: 400; color: #fff; }
    .auth-visual .logo .name b { font-weight: 800; }
    .auth-visual h2 { font-size: 2.1rem; font-weight: 800; color: #fff; line-height: 1.25; margin-bottom: 1rem; }
    .auth-visual p  { color: rgba(255,255,255,.72); font-size: .95rem; line-height: 1.7; max-width: 340px; margin-bottom: 2.5rem; }

    .perks { list-style: none; display: flex; flex-direction: column; gap: .9rem; }
    .perks li { display: flex; align-items: center; gap: .8rem; color: rgba(255,255,255,.85); font-size: .9rem; font-weight: 500; }
    .perk-icon { width: 36px; height: 36px; background: rgba(255,255,255,.12); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1rem; flex-shrink: 0; }

    .no-account { margin-top: 3rem; display: flex; align-items: center; gap: .5rem; font-size: .87rem; color: rgba(255,255,255,.55); }
    .no-account a { color: #86efac; font-weight: 700; text-decoration: none; }
    .no-account a:hover { color: #fff; }

    /* ── Formulario ── */
    .auth-form-wrap {
        flex: 1.1; display: flex; align-items: center; justify-content: center;
        padding: 3rem 2rem; background: #fff;
    }
    .auth-form-inner { width: 100%; max-width: 420px; }

    .form-header { margin-bottom: 2rem; }
    .form-header .step-tag {
        display: inline-flex; align-items: center; gap: .4rem;
        background: #f0fdf4; color: #16a34a;
        font-size: .76rem; font-weight: 700; letter-spacing: .06em;
        text-transform: uppercase; padding: .3rem .8rem; border-radius: 20px; margin-bottom: .9rem;
    }
    .form-header h1 { font-size: 1.7rem; font-weight: 800; color: #111827; margin-bottom: .35rem; }
    .form-header p  { color: #6b7280; font-size: .9rem; }

    .demo-box {
        background: #f0fdf4; border: 1px solid #bbf7d0;
        border-radius: 10px; padding: .85rem 1rem; margin-bottom: 1.5rem; font-size: .82rem; color: #166534;
    }
    .demo-box strong { display: block; margin-bottom: .4rem; font-size: .83rem; }
    .demo-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: .25rem; }
    .demo-row:last-child { margin-bottom: 0; }
    .demo-row span { color: #374151; }
    .demo-row code { background: #dcfce7; padding: .1rem .45rem; border-radius: 5px; font-size: .79rem; font-family: monospace; }

    .form-group { margin-bottom: 1.1rem; }
    .form-group label { display: block; font-size: .82rem; font-weight: 600; color: #374151; margin-bottom: .4rem; }
    .input-wrap { position: relative; }
    .input-icon { position: absolute; left: .85rem; top: 50%; transform: translateY(-50%); font-size: 1rem; pointer-events: none; }
    .form-control {
        width: 100%; padding: .74rem 1rem .74rem 2.5rem;
        border: 1.5px solid #e5e7eb; border-radius: 10px;
        font-size: .93rem; font-family: inherit; color: #111827;
        background: #f9fafb; outline: none;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .form-control:focus { border-color: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,.12); background: #fff; }
    .form-control.is-invalid { border-color: #ef4444; background: #fff5f5; }
    .invalid-feedback { color: #ef4444; font-size: .8rem; margin-top: .3rem; display: flex; align-items: center; gap: .3rem; }

    .form-options { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.3rem; }
    .check-label { display: flex; align-items: center; gap: .45rem; font-size: .85rem; color: #4b5563; cursor: pointer; }
    .check-label input { accent-color: #16a34a; width: 15px; height: 15px; }
    .forgot-link { font-size: .83rem; color: #16a34a; text-decoration: none; font-weight: 600; }
    .forgot-link:hover { text-decoration: underline; }

    .btn-submit {
        width: 100%; padding: .88rem; background: #16a34a; color: #fff; border: none;
        border-radius: 12px; font-size: .97rem; font-weight: 700; font-family: inherit;
        cursor: pointer; display: flex; align-items: center; justify-content: center; gap: .5rem;
        transition: background .2s, transform .15s, box-shadow .2s;
        box-shadow: 0 4px 14px rgba(22,163,74,.3);
    }
    .btn-submit:hover { background: #15803d; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(22,163,74,.35); }

    .divider { display: flex; align-items: center; gap: .8rem; margin: 1.3rem 0; }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #f0f0f0; }
    .divider span { font-size: .8rem; color: #d1d5db; }

    .register-link { text-align: center; font-size: .9rem; color: #6b7280; }
    .register-link a { color: #16a34a; font-weight: 700; text-decoration: none; }
    .register-link a:hover { text-decoration: underline; }
</style>
@endpush

@section('content')
<div style="margin: -2rem -1.5rem;">
<div class="auth-page">

    <!-- ── Panel visual ── -->
    <div class="auth-visual">
        <div class="logo">
            <span>🛒</span>
            <span class="name">Super<b>Fresco</b></span>
        </div>
        <h2>Bienvenido<br>de vuelta</h2>
        <p>Accede a tu cuenta y disfruta de los mejores productos frescos con envío a domicilio.</p>

        <ul class="perks">
            <li><span class="perk-icon">📦</span> Gestiona tus pedidos en tiempo real</li>
            <li><span class="perk-icon">🏷️</span> Accede a ofertas exclusivas para miembros</li>
            <li><span class="perk-icon">🔒</span> Tu información está 100% segura</li>
            <li><span class="perk-icon">⚡</span> Proceso de compra rápido y sencillo</li>
        </ul>

        <div class="no-account">
            ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate gratis →</a>
        </div>
    </div>

    <!-- ── Formulario ── -->
    <div class="auth-form-wrap">
        <div class="auth-form-inner">

            <div class="form-header">
                <div class="step-tag">🔐 Acceso seguro</div>
                <h1>Iniciar sesión</h1>
                <p>Ingresa tus credenciales para continuar</p>
            </div>

            <form action="{{ route('login') }}" method="POST" novalidate>
                @csrf

                <div class="form-group">
                    <label for="email">Correo electrónico</label>
                    <div class="input-wrap">
                        <span class="input-icon">✉️</span>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="tu@correo.com"
                            autocomplete="email" autofocus>
                    </div>
                    @error('email')<span class="invalid-feedback">⚠ {{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <div class="input-wrap">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="••••••••" autocomplete="current-password">
                    </div>
                    @error('password')<span class="invalid-feedback">⚠ {{ $message }}</span>@enderror
                </div>

                <div class="form-options">
                    <label class="check-label">
                        <input type="checkbox" name="remember" value="1"> Recordarme
                    </label>
                    <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn-submit">Iniciar sesión →</button>
            </form>

            <div class="divider"><span>o</span></div>
            <div class="register-link">
                ¿No tienes cuenta? <a href="{{ route('register') }}">Regístrate gratis</a>
            </div>

        </div>
    </div>

</div>
</div>
@endsection
