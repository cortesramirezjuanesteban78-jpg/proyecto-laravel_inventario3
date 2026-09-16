<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SuperFresco') — SuperFresco</title>
    
    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-900: #042217;
            --primary-800: #063826;
            --primary-700: #0b4e37;
            --primary-600: #106f4e;
            --primary-500: #159c6c;
            --primary-100: #d1fae5;
            --primary-50: #ecfdf5;

            --accent-gold: #d97706;
            --accent-coral: #f43f5e;

            --text-dark: #0f172a;
            --text-muted: #64748b;
            --bg-canvas: #f8fafc;
            --surface-card: #ffffff;
            --border-subtle: #e2e8f0;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-canvas);
            color: var(--text-dark);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, .brand-name {
            font-family: 'Outfit', sans-serif;
        }

        /* ── Navbar ── */
        .navbar {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            padding: 0 3.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 70px;
            border-bottom: 1px solid var(--border-subtle);
            box-shadow: 0 2px 10px rgba(15, 23, 42, 0.03);
            position: sticky; top: 0; z-index: 100;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
        }
        .brand-logo-wrap {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-700) 0%, var(--primary-500) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.2rem;
            box-shadow: 0 4px 10px rgba(16, 111, 78, 0.2);
        }
        .navbar-brand .brand-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: -0.4px;
        }
        .navbar-brand .brand-name b {
            background: linear-gradient(135deg, var(--primary-700) 0%, var(--primary-500) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-greeting {
            font-size: 0.88rem;
            color: var(--text-muted);
        }
        .user-greeting strong {
            color: var(--text-dark);
            font-weight: 700;
        }

        .btn-logout {
            background: #fff;
            border: 1.5px solid var(--border-subtle);
            color: var(--text-muted);
            padding: 0.45rem 1.1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s ease;
        }
        .btn-logout:hover {
            border-color: var(--accent-coral);
            color: var(--accent-coral);
            background: #fff1f2;
            transform: translateY(-1px);
        }

        .btn-back {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            transition: all 0.2s;
        }
        .btn-back:hover {
            color: var(--primary-700);
            background: var(--primary-50);
        }

        /* ── Alerts ── */
        .alert {
            padding: 0.9rem 1.3rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.92rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }
        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .alert-danger {
            background: #fff1f2;
            color: #9f1239;
            border: 1px solid #fecdd3;
        }
        .alert-warning {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        /* ── Main Container ── */
        .main-content {
            max-width: 1240px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        @media (max-width: 768px) {
            .navbar { padding: 0 1.5rem; }
            .main-content { padding: 0 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar">
    <a href="{{ route('index') }}" class="navbar-brand">
        <div class="brand-logo-wrap">🛒</div>
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
            <a href="{{ route('index') }}" class="btn-back">← Volver a la Tienda</a>
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
