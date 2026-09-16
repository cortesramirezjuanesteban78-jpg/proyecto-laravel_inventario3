<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SuperFresco')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f5f7f5; color: #1a202c; min-height: 100vh; }

        /* ── Navbar ── */
        .navbar {
            background: #fff;
            padding: 0 3rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 66px;
            box-shadow: 0 1px 0 #e5e7eb, 0 4px 16px rgba(0,0,0,.05);
            position: sticky; top: 0; z-index: 100;
        }
        .navbar-brand { display: flex; align-items: center; gap: .5rem; text-decoration: none; }
        .navbar-brand .brand-logo { font-size: 1.6rem; }
        .navbar-brand .brand-name { font-size: 1.2rem; font-weight: 400; color: #1a202c; }
        .navbar-brand .brand-name b { color: #16a34a; font-weight: 800; }

        .navbar-right { display: flex; align-items: center; gap: .8rem; }

        /* Solo se muestra cuando está autenticado */
        .user-greeting { font-size: .85rem; color: #6b7280; }
        .user-greeting strong { color: #111827; font-weight: 600; }

        .btn-logout {
            background: none; border: 1.5px solid #e5e7eb;
            color: #4b5563; padding: .45rem 1rem; border-radius: 20px;
            font-size: .85rem; font-weight: 600; cursor: pointer;
            font-family: inherit; transition: all .2s;
        }
        .btn-logout:hover { border-color: #ef4444; color: #ef4444; background: #fff5f5; }

        /* Volver al inicio */
        .btn-back {
            display: flex; align-items: center; gap: .4rem;
            color: #6b7280; text-decoration: none; font-size: .88rem;
            font-weight: 500; padding: .45rem .9rem; border-radius: 8px;
            transition: all .2s;
        }
        .btn-back:hover { color: #16a34a; background: #f0fdf4; }

        /* ── Alerts ── */
        .alert {
            padding: .85rem 1.2rem; border-radius: 10px;
            margin-bottom: 1.2rem; font-size: .9rem;
            display: flex; align-items: center; gap: .6rem;
        }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-danger  { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .alert-warning { background: #fef3c7; color: #92400e; border: 1px solid #fcd34d; }

        /* ── Main ── */
        .main-content { max-width: 1200px; margin: 2rem auto; padding: 0 1.5rem; }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar">
    <a href="{{ route('index') }}" class="navbar-brand">
        <span class="brand-logo">🛒</span>
        <span class="brand-name">Super<b>Fresco</b></span>
    </a>

    <div class="navbar-right">
        @auth
            <span class="user-greeting">Hola, <strong>{{ Auth::user()->nombres }}</strong></span>
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="btn-logout">Cerrar sesión</button>
            </form>
        @else
            <a href="{{ route('index') }}" class="btn-back">← Volver al inicio</a>
        @endauth
    </div>
</nav>

<main class="main-content">
    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">❌ {{ session('error') }}</div>
    @endif
    @yield('content')
</main>

@stack('scripts')
</body>
</html>
