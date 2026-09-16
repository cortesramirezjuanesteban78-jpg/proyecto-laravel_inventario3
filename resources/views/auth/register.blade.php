@extends('layouts.app')
@section('title', 'Crear cuenta — SuperFresco')

@push('styles')
<style>
    body { background: #f5f7f5; }

    .auth-page {
        min-height: calc(100vh - 66px);
        display: flex; align-items: stretch;
    }

    /* ── Panel izquierdo (visual) ── */
    .auth-visual {
        flex: 1;
        background: linear-gradient(145deg, #052e16 0%, #14532d 45%, #16a34a 100%);
        display: flex; flex-direction: column;
        justify-content: center; align-items: flex-start;
        padding: 4rem 3.5rem;
        position: relative; overflow: hidden;
    }
    .auth-visual::before {
        content: '';
        position: absolute; top: -120px; right: -120px;
        width: 420px; height: 420px; border-radius: 50%;
        background: rgba(255,255,255,.05);
    }
    .auth-visual::after {
        content: '';
        position: absolute; bottom: -80px; left: -80px;
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
    .perks li {
        display: flex; align-items: center; gap: .8rem;
        color: rgba(255,255,255,.85); font-size: .9rem; font-weight: 500;
    }
    .perks li .perk-icon {
        width: 36px; height: 36px; background: rgba(255,255,255,.12);
        border-radius: 10px; display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }

    .already-account {
        margin-top: 3rem;
        display: flex; align-items: center; gap: .5rem;
        font-size: .87rem; color: rgba(255,255,255,.55);
    }
    .already-account a {
        color: #86efac; font-weight: 700; text-decoration: none;
        transition: color .2s;
    }
    .already-account a:hover { color: #fff; }

    /* ── Panel derecho (formulario) ── */
    .auth-form-wrap {
        flex: 1.1;
        display: flex; align-items: center; justify-content: center;
        padding: 3rem 2rem;
        background: #fff;
    }
    .auth-form-inner { width: 100%; max-width: 460px; }

    .form-header { margin-bottom: 2rem; }
    .form-header .step-tag {
        display: inline-flex; align-items: center; gap: .4rem;
        background: #f0fdf4; color: #16a34a;
        font-size: .76rem; font-weight: 700; letter-spacing: .06em;
        text-transform: uppercase; padding: .3rem .8rem; border-radius: 20px;
        margin-bottom: .9rem;
    }
    .form-header h1 { font-size: 1.7rem; font-weight: 800; color: #111827; margin-bottom: .35rem; }
    .form-header p  { color: #6b7280; font-size: .9rem; }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    @media(max-width: 480px) { .form-row { grid-template-columns: 1fr; } }

    .form-group { margin-bottom: 1.1rem; }
    .form-group label {
        display: flex; align-items: center; gap: .3rem;
        font-size: .82rem; font-weight: 600; color: #374151; margin-bottom: .4rem;
    }
    .req { color: #ef4444; font-size: .8rem; }
    .opt { color: #9ca3af; font-size: .75rem; font-weight: 400; }

    .input-wrap { position: relative; }
    .input-icon {
        position: absolute; left: .85rem; top: 50%; transform: translateY(-50%);
        font-size: 1rem; pointer-events: none;
    }
    .form-control {
        width: 100%; padding: .72rem 1rem .72rem 2.5rem;
        border: 1.5px solid #e5e7eb; border-radius: 10px;
        font-size: .93rem; font-family: inherit; color: #111827;
        background: #f9fafb; outline: none;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .form-control:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22,163,74,.12);
        background: #fff;
    }
    .form-control.is-invalid { border-color: #ef4444; background: #fff5f5; }
    .form-control.is-invalid:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.1); }

    /* campo sin ícono */
    .form-control.no-icon { padding-left: 1rem; }

    .invalid-feedback { color: #ef4444; font-size: .8rem; margin-top: .3rem; display: flex; align-items: center; gap: .3rem; }
    .password-hint   { color: #9ca3af; font-size: .78rem; margin-top: .3rem; }

    /* fuerza de contraseña */
    .password-strength { display: flex; gap: .3rem; margin-top: .5rem; }
    .strength-bar { flex: 1; height: 3px; background: #e5e7eb; border-radius: 2px; transition: background .3s; }
    .strength-bar.weak   { background: #ef4444; }
    .strength-bar.medium { background: #f59e0b; }
    .strength-bar.strong { background: #16a34a; }

    .btn-submit {
        width: 100%; padding: .88rem;
        background: #16a34a; color: #fff; border: none;
        border-radius: 12px; font-size: .97rem; font-weight: 700;
        font-family: inherit; cursor: pointer; margin-top: .5rem;
        display: flex; align-items: center; justify-content: center; gap: .5rem;
        transition: background .2s, transform .15s, box-shadow .2s;
        box-shadow: 0 4px 14px rgba(22,163,74,.3);
    }
    .btn-submit:hover { background: #15803d; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(22,163,74,.35); }

    .terms-text {
        font-size: .79rem; color: #9ca3af; text-align: center;
        margin-top: .9rem; line-height: 1.6;
    }
    .terms-text a { color: #16a34a; text-decoration: none; }
    .terms-text a:hover { text-decoration: underline; }

    .divider { display: flex; align-items: center; gap: .8rem; margin: 1.3rem 0; }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #f0f0f0; }
    .divider span { font-size: .8rem; color: #d1d5db; }

    .login-link { text-align: center; font-size: .9rem; color: #6b7280; }
    .login-link a { color: #16a34a; font-weight: 700; text-decoration: none; }
    .login-link a:hover { text-decoration: underline; }
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
        <h2>Únete a miles de<br>clientes felices</h2>
        <p>Crea tu cuenta gratis y disfruta de los mejores productos frescos con entrega a domicilio.</p>

        <ul class="perks">
            <li><span class="perk-icon">🚚</span> Envío gratis en pedidos mayores a $50</li>
            <li><span class="perk-icon">🌿</span> Productos 100% frescos garantizados</li>
            <li><span class="perk-icon">🏷️</span> Ofertas y descuentos exclusivos</li>
            <li><span class="perk-icon">🎧</span> Soporte al cliente 24/7</li>
        </ul>

        <div class="already-account">
            ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión →</a>
        </div>
    </div>

    <!-- ── Formulario ── -->
    <div class="auth-form-wrap">
        <div class="auth-form-inner">

            <div class="form-header">
                <div class="step-tag">✨ Es gratis</div>
                <h1>Crear cuenta</h1>
                <p>Completa el formulario para empezar a comprar</p>
            </div>

            <form action="{{ route('register') }}" method="POST" novalidate>
                @csrf

                <div class="form-row">
                    <div class="form-group">
                        <label for="nombres">Nombres <span class="req">*</span></label>
                        <div class="input-wrap">
                            <span class="input-icon">👤</span>
                            <input type="text" id="nombres" name="nombres"
                                class="form-control @error('nombres') is-invalid @enderror"
                                value="{{ old('nombres') }}" placeholder="Juan" autofocus>
                        </div>
                        @error('nombres')<span class="invalid-feedback">⚠ {{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="apellidos">Apellidos <span class="req">*</span></label>
                        <div class="input-wrap">
                            <span class="input-icon">👤</span>
                            <input type="text" id="apellidos" name="apellidos"
                                class="form-control @error('apellidos') is-invalid @enderror"
                                value="{{ old('apellidos') }}" placeholder="Pérez">
                        </div>
                        @error('apellidos')<span class="invalid-feedback">⚠ {{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Correo electrónico <span class="req">*</span></label>
                    <div class="input-wrap">
                        <span class="input-icon">✉️</span>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="tu@correo.com">
                    </div>
                    @error('email')<span class="invalid-feedback">⚠ {{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label for="telefono">Teléfono <span class="opt">(opcional)</span></label>
                    <div class="input-wrap">
                        <span class="input-icon">📞</span>
                        <input type="tel" id="telefono" name="telefono"
                            class="form-control @error('telefono') is-invalid @enderror"
                            value="{{ old('telefono') }}" placeholder="3001234567">
                    </div>
                    @error('telefono')<span class="invalid-feedback">⚠ {{ $message }}</span>@enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Contraseña <span class="req">*</span></label>
                        <div class="input-wrap">
                            <span class="input-icon">🔒</span>
                            <input type="password" id="password" name="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Mínimo 6 caracteres" oninput="checkStrength(this.value)">
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar" id="sb1"></div>
                            <div class="strength-bar" id="sb2"></div>
                            <div class="strength-bar" id="sb3"></div>
                        </div>
                        @error('password')<span class="invalid-feedback">⚠ {{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirmar <span class="req">*</span></label>
                        <div class="input-wrap">
                            <span class="input-icon">🔒</span>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="form-control" placeholder="Repite tu contraseña">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Crear mi cuenta →</button>

                <p class="terms-text">
                    Al registrarte aceptas nuestros <a href="#">Términos de servicio</a>
                    y la <a href="#">Política de privacidad</a>.
                </p>
            </form>

            <div class="divider"><span>o</span></div>
            <div class="login-link">
                ¿Ya tienes cuenta? <a href="{{ route('login') }}">Iniciar sesión</a>
            </div>

        </div>
    </div>

</div>
</div>
@endsection

@push('scripts')
<script>
function checkStrength(val) {
    const bars = [document.getElementById('sb1'), document.getElementById('sb2'), document.getElementById('sb3')];
    bars.forEach(b => b.className = 'strength-bar');
    if (val.length === 0) return;
    if (val.length >= 4)  bars[0].classList.add('weak');
    if (val.length >= 7)  bars[1].classList.add('medium');
    if (val.length >= 10) bars[2].classList.add('strong');
}
</script>
@endpush
