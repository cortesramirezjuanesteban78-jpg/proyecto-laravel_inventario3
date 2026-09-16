@extends('layouts.app')
@section('title', 'Iniciar sesión — SuperFresco')

@push('styles')
<style>
    body { background: #f8fafc; }

    .auth-page {
        min-height: calc(100vh - 70px);
        display: flex; align-items: stretch;
    }

    /* ── Panel visual ── */
    .auth-visual {
        flex: 1;
        background: linear-gradient(145deg, #042217 0%, #063826 45%, #0b4e37 80%, #106f4e 100%);
        display: flex; flex-direction: column;
        justify-content: center; align-items: flex-start;
        padding: 4.5rem 4rem;
        position: relative; overflow: hidden;
    }
    .auth-visual::before {
        content: ''; position: absolute; top: -120px; right: -120px;
        width: 440px; height: 440px; border-radius: 50%;
        background: radial-gradient(circle, rgba(34, 197, 132, 0.18) 0%, rgba(255,255,255,0) 70%);
        pointer-events: none;
    }
    .auth-visual::after {
        content: ''; position: absolute; bottom: -80px; left: -80px;
        width: 320px; height: 320px; border-radius: 50%;
        background: radial-gradient(circle, rgba(217, 119, 6, 0.15) 0%, rgba(255,255,255,0) 70%);
        pointer-events: none;
    }
    .auth-visual .logo { display: flex; align-items: center; gap: 0.65rem; margin-bottom: 3rem; }
    .auth-visual .logo-icon {
        width: 44px; height: 44px; border-radius: 12px;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.3);
        backdrop-filter: blur(10px);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
    }
    .auth-visual .logo .name { font-size: 1.45rem; font-weight: 700; color: #fff; letter-spacing: -0.4px; }
    .auth-visual .logo .name b { color: #34d399; font-weight: 800; }
    .auth-visual h2 {
        font-size: 2.3rem; font-weight: 900; color: #fff;
        line-height: 1.2; margin-bottom: 1rem; letter-spacing: -0.02em;
    }
    .auth-visual p {
        color: rgba(255, 255, 255, 0.85); font-size: 0.98rem;
        line-height: 1.7; max-width: 360px; margin-bottom: 2.8rem;
    }

    .perks { list-style: none; display: flex; flex-direction: column; gap: 1rem; }
    .perks li {
        display: flex; align-items: center; gap: 0.9rem;
        color: rgba(255, 255, 255, 0.9); font-size: 0.92rem; font-weight: 500;
    }
    .perk-icon {
        width: 38px; height: 38px;
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }

    .no-account {
        margin-top: 3.5rem; display: flex; align-items: center; gap: 0.5rem;
        font-size: 0.9rem; color: rgba(255, 255, 255, 0.7);
    }
    .no-account a { color: #6ee7b7; font-weight: 700; text-decoration: none; }
    .no-account a:hover { color: #fff; text-decoration: underline; }

    /* ── Formulario ── */
    .auth-form-wrap {
        flex: 1.1; display: flex; align-items: center; justify-content: center;
        padding: 3.5rem 2.5rem; background: #ffffff;
    }
    .auth-form-inner { width: 100%; max-width: 440px; }

    .form-header { margin-bottom: 2.2rem; }
    .form-header .step-tag {
        display: inline-flex; align-items: center; gap: 0.4rem;
        background: #ecfdf5; color: #065f46;
        border: 1px solid #a7f3d0;
        font-size: 0.76rem; font-weight: 800; letter-spacing: 0.08em;
        text-transform: uppercase; padding: 0.35rem 0.9rem; border-radius: 20px; margin-bottom: 1rem;
    }
    .form-header h1 {
        font-size: 1.9rem; font-weight: 800; color: #0f172a;
        margin-bottom: 0.4rem; letter-spacing: -0.02em;
    }
    .form-header p { color: #64748b; font-size: 0.94rem; }

    .form-group { margin-bottom: 1.25rem; }
    .form-group label {
        display: block; font-size: 0.85rem; font-weight: 700;
        color: #334155; margin-bottom: 0.45rem;
    }
    .input-wrap { position: relative; }
    .input-icon {
        position: absolute; left: 0.95rem; top: 50%; transform: translateY(-50%);
        font-size: 1.05rem; pointer-events: none; color: #64748b;
    }
    .form-control {
        width: 100%; padding: 0.8rem 1.1rem 0.8rem 2.75rem;
        border: 1.5px solid #e2e8f0; border-radius: 12px;
        font-size: 0.95rem; font-family: inherit; color: #0f172a;
        background: #f8fafc; outline: none;
        transition: all 0.25s ease;
    }
    .form-control:focus {
        border-color: #159c6c;
        box-shadow: 0 0 0 3.5px rgba(21, 156, 108, 0.15);
        background: #ffffff;
    }
    .form-control.is-invalid { border-color: #f43f5e; background: #fff1f2; }
    .invalid-feedback {
        color: #f43f5e; font-size: 0.82rem; margin-top: 0.35rem;
        display: flex; align-items: center; gap: 0.35rem; font-weight: 600;
    }

    .form-options {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    .check-label {
        display: flex; align-items: center; gap: 0.5rem;
        font-size: 0.88rem; color: #475569; cursor: pointer; font-weight: 500;
    }
    .check-label input { accent-color: #106f4e; width: 16px; height: 16px; }
    .forgot-link {
        font-size: 0.86rem; color: #106f4e; text-decoration: none; font-weight: 700;
    }
    .forgot-link:hover { text-decoration: underline; color: #063826; }

    .btn-submit {
        width: 100%; padding: 0.9rem;
        background: linear-gradient(135deg, #0b4e37 0%, #159c6c 100%);
        color: #fff; border: none;
        border-radius: 12px; font-size: 0.98rem; font-weight: 800; font-family: inherit;
        cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.55rem;
        transition: all 0.25s ease;
        box-shadow: 0 4px 16px rgba(16, 111, 78, 0.3);
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(16, 111, 78, 0.4);
        filter: brightness(1.05);
    }

    .divider { display: flex; align-items: center; gap: 0.8rem; margin: 1.6rem 0; }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }
    .divider span { font-size: 0.82rem; color: #94a3b8; font-weight: 600; }

    .register-link { text-align: center; font-size: 0.92rem; color: #64748b; }
    .register-link a { color: #106f4e; font-weight: 700; text-decoration: none; }
    .register-link a:hover { text-decoration: underline; color: #042217; }

    @media (max-width: 900px) {
        .auth-page { flex-direction: column; }
        .auth-visual { padding: 3rem 1.5rem; }
        .auth-form-wrap { padding: 3rem 1.5rem; }
    }
</style>
@endpush

@section('content')
<div style="margin: -2rem -2rem;">
<div class="auth-page">

    <!-- ── Panel visual ── -->
    <div class="auth-visual">
        <div class="logo">
            <div class="logo-icon">🛒</div>
            <span class="name">Super<b>Fresco</b></span>
        </div>
        <h2>Bienvenido<br>de vuelta</h2>
        <p>Ingresa a tu cuenta para gestionar pedidos, revisar promociones gourmet y comprar productos frescos del día.</p>

        <ul class="perks">
            <li><span class="perk-icon">📦</span> Seguimiento de pedidos en tiempo real</li>
            <li><span class="perk-icon">✨</span> Ofertas y descuentos semanales de temporada</li>
            <li><span class="perk-icon">🔒</span> Plataforma protegida y pagos 100% seguros</li>
            <li><span class="perk-icon">⚡</span> Entregas directas a domicilio sin demoras</li>
        </ul>

        <div class="no-account">
            ¿Aún no tienes cuenta? <a href="{{ route('register') }}">Regístrate gratis →</a>
        </div>
    </div>

    <!-- ── Formulario ── -->
    <div class="auth-form-wrap">
        <div class="auth-form-inner">

            <div class="form-header">
                <div class="step-tag">🔐 Acceso Seguro</div>
                <h1>Iniciar Sesión</h1>
                <p>Ingresa tus credenciales para acceder al sistema</p>
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
                        <input type="checkbox" name="remember" value="1"> Recordar mi sesión
                    </label>
                    <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn-submit">
                    <span>Ingresar a mi cuenta</span>
                    <span>→</span>
                </button>
            </form>

            <div class="divider"><span>o también</span></div>
            <div class="register-link">
                ¿Aún no eres miembro? <a href="{{ route('register') }}">Crear una cuenta nueva</a>
            </div>

        </div>
    </div>

</div>
</div>
@endsection
