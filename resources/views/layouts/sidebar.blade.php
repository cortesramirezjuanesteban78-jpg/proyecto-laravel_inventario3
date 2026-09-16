<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SuperFresco') — SuperFresco</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f4f0; color: #1a202c; display: flex; min-height: 100vh; }

        /* ══════════ SIDEBAR ══════════ */
        .sidebar {
            width: 220px; min-width: 220px; background: #fff;
            border-right: 1px solid #e8ede8;
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0; z-index: 50;
        }
        .sidebar-logo { padding: 1.4rem 1.4rem 1rem; border-bottom: 1px solid #f0f4f0; }
        .sidebar-logo a { display: flex; align-items: center; gap: .5rem; text-decoration: none; }
        .sidebar-logo .icon { font-size: 1.5rem; }
        .sidebar-logo .name { font-size: 1.1rem; font-weight: 400; color: #111827; }
        .sidebar-logo .name b { color: #16a34a; font-weight: 800; }
        .sidebar-logo .role-tag { font-size: .72rem; color: #6b7280; margin-top: .2rem; padding-left: .1rem; }

        .menu-label {
            font-size: .68rem; font-weight: 700; letter-spacing: .1em;
            text-transform: uppercase; color: #9ca3af;
            padding: 1.2rem 1.4rem .5rem;
        }
        .nav-list { list-style: none; padding: 0 .7rem; flex: 1; }
        .nav-list li { margin-bottom: .15rem; }
        .nav-link {
            display: flex; align-items: center; gap: .75rem;
            padding: .6rem .75rem; border-radius: 10px;
            text-decoration: none; color: #4b5563;
            font-size: .88rem; font-weight: 500; transition: all .18s;
        }
        .nav-link:hover { background: #f0fdf4; color: #16a34a; }
        .nav-link.active { background: #dcfce7; color: #15803d; font-weight: 700; }
        .nav-link .nav-icon {
            width: 30px; height: 30px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: .95rem; flex-shrink: 0; transition: background .18s;
        }
        .nav-link.active .nav-icon { background: #16a34a; }
        .nav-link:hover .nav-icon { background: #bbf7d0; }

        .sidebar-footer { border-top: 1px solid #f0f4f0; padding: 1rem 1.2rem .8rem; }
        .user-profile {
            display: flex; align-items: center; gap: .7rem;
            padding: .55rem .7rem; border-radius: 10px;
            background: #f9fafb; margin-bottom: .7rem;
        }
        .user-avatar {
            width: 34px; height: 34px; border-radius: 10px;
            background: #16a34a; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: .9rem; font-weight: 800; flex-shrink: 0;
        }
        .user-info-side .uname { font-size: .85rem; font-weight: 700; color: #111827; line-height: 1.2; }
        .user-info-side .uemail { font-size: .72rem; color: #9ca3af; }
        .btn-logout-side {
            width: 100%; display: flex; align-items: center; gap: .5rem;
            padding: .5rem .7rem; border-radius: 8px;
            background: none; border: none; cursor: pointer;
            font-family: inherit; font-size: .84rem;
            color: #ef4444; font-weight: 600; transition: background .18s;
        }
        .btn-logout-side:hover { background: #fff5f5; }

        /* ══════════ MAIN ══════════ */
        .main-panel { margin-left: 220px; flex: 1; min-height: 100vh; background: #f0f4f0; }

        .page-header-bar {
            background: #fff; border-bottom: 1px solid #e8ede8;
            padding: 1.2rem 2.2rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .page-header-bar h1 { font-size: 1.5rem; font-weight: 800; color: #111827; }
        .page-header-bar .subtitle { font-size: .84rem; color: #6b7280; margin-top: .15rem; }

        .page-content { padding: 2rem 2.2rem; }

        /* Flash messages */
        .flash-success {
            background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46;
            padding: .85rem 1.2rem; border-radius: 10px; margin-bottom: 1.2rem;
            font-size: .9rem; font-weight: 600; display: flex; align-items: center; gap: .5rem;
        }
        .flash-error {
            background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b;
            padding: .85rem 1.2rem; border-radius: 10px; margin-bottom: 1.2rem;
            font-size: .9rem; font-weight: 600; display: flex; align-items: center; gap: .5rem;
        }
        .flash-close {
            margin-left: auto; background: none; border: none; cursor: pointer;
            font-size: 1.1rem; color: inherit; opacity: .6;
        }
        .flash-close:hover { opacity: 1; }
    </style>
    @stack('styles')
</head>
<body>

@php $activeMenu = $activeMenu ?? \Illuminate\Support\Facades\Route::currentRouteName(); @endphp

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <a href="{{ route('dashboard') }}">
            <span class="icon">🛒</span>
            <div>
                <div class="name">Super<b>Fresco</b></div>
                <div class="role-tag">
                    @if(Auth::user()->rol === 'administrador') Panel Admin
                    @elseif(Auth::user()->rol === 'empleado') Panel Empleado
                    @else Panel Cliente @endif
                </div>
            </div>
        </a>
    </div>

    <div class="menu-label">Menú</div>

    <ul class="nav-list">
        <li>
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ Str::startsWith($activeMenu, 'dashboard') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span>
                @if(Auth::user()->rol === 'empleado') Inicio @else Dashboard @endif
            </a>
        </li>
        @if(Auth::user()->rol === 'administrador')
        <li>
            <a href="{{ route('usuarios.index') }}"
               class="nav-link {{ Str::startsWith($activeMenu, 'usuarios') ? 'active' : '' }}">
                <span class="nav-icon">👥</span> Usuarios
            </a>
        </li>
        <li>
            <a href="{{ route('categorias.index') }}"
               class="nav-link {{ Str::startsWith($activeMenu, 'categorias') ? 'active' : '' }}">
                <span class="nav-icon">🏷️</span> Categorías
            </a>
        </li>
        @endif
        <li>
            <a href="{{ route('productos.index') }}"
               class="nav-link {{ Str::startsWith($activeMenu, 'productos') ? 'active' : '' }}">
                <span class="nav-icon">📦</span> Productos
            </a>
        </li>
        <li>
            <a href="{{ route('inventario.index') }}"
               class="nav-link {{ Str::startsWith($activeMenu, 'inventario') ? 'active' : '' }}">
                <span class="nav-icon">🏗️</span> Inventario
            </a>
        </li>
        @if(Auth::user()->rol === 'administrador')
        <li>
            <a href="{{ route('ventas.index') }}"
               class="nav-link {{ Str::startsWith($activeMenu, 'ventas') ? 'active' : '' }}">
                <span class="nav-icon">🛒</span> Ventas
            </a>
        </li>
        <li>
            <a href="{{ route('proveedores.index') }}"
               class="nav-link {{ Str::startsWith($activeMenu, 'proveedores') ? 'active' : '' }}">
                <span class="nav-icon">🚚</span> Proveedores
            </a>
        </li>
        @endif
        <li>
            <a href="{{ route('reportes.index') }}"
               class="nav-link {{ Str::startsWith($activeMenu, 'reportes') ? 'active' : '' }}">
                <span class="nav-icon">📊</span> Reportes
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->nombres, 0, 1)) }}</div>
            <div class="user-info-side">
                <div class="uname">{{ Auth::user()->nombres }}</div>
                <div class="uemail">{{ Str::limit(Auth::user()->email, 20) }}</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout-side">🚪 Cerrar Sesión</button>
        </form>
    </div>
</aside>

<!-- MAIN -->
<main class="main-panel">
    <!-- Page Header Bar -->
    <div class="page-header-bar">
        <div>
            <h1>@yield('page-title', 'Panel')</h1>
            <div class="subtitle">@yield('page-subtitle')</div>
        </div>
        <div>@yield('page-action')</div>
    </div>

    <!-- Content -->
    <div class="page-content">
        @if(session('success'))
        <div class="flash-success" id="flash-ok">
            ✅ {{ session('success') }}
            <button class="flash-close" onclick="document.getElementById('flash-ok').remove()">✕</button>
        </div>
        @endif
        @if(session('error'))
        <div class="flash-error" id="flash-err">
            ❌ {{ session('error') }}
            <button class="flash-close" onclick="document.getElementById('flash-err').remove()">✕</button>
        </div>
        @endif

        @yield('content')
    </div>
</main>

@stack('scripts')
</body>
</html>
