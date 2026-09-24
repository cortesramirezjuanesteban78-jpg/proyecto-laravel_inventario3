<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SuperFresco') — Panel de Gestión</title>
    
    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
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
            --accent-gold-light: #fef3c7;
            --accent-coral: #f43f5e;
            --accent-coral-light: #fff1f2;

            --text-dark: #0f172a;
            --text-muted: #64748b;
            --text-light: #94a3b8;

            --bg-canvas: #f8fafc;
            --surface-card: #ffffff;
            --border-subtle: #e2e8f0;
            --border-light: rgba(226, 232, 240, 0.8);

            --shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.04);
            --shadow-md: 0 4px 16px -2px rgba(15, 23, 42, 0.06);
            --shadow-lg: 0 16px 32px -8px rgba(15, 23, 42, 0.08);
            --shadow-glow: 0 4px 16px rgba(16, 111, 78, 0.25);
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-canvas);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, .brand-name, .sc-num {
            font-family: 'Outfit', sans-serif;
        }

        /* ══════════ SIDEBAR ELEGANTE ══════════ */
        .sidebar {
            width: 240px;
            min-width: 240px;
            background: #ffffff;
            border-right: 1px solid var(--border-subtle);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 50;
            box-shadow: 2px 0 12px rgba(15, 23, 42, 0.02);
        }
        .sidebar-logo {
            padding: 1.4rem 1.4rem 1.2rem;
            border-bottom: 1px solid var(--border-subtle);
        }
        .sidebar-logo a {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
        }
        .sidebar-logo .icon {
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
            flex-shrink: 0;
        }
        .sidebar-logo .name {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: -0.3px;
            line-height: 1.1;
        }
        .sidebar-logo .name b {
            background: linear-gradient(135deg, var(--primary-700) 0%, var(--primary-500) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }
        .sidebar-logo .role-tag {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--primary-600);
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-top: 3px;
        }

        .menu-label {
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-light);
            padding: 1.4rem 1.4rem 0.5rem;
        }
        .nav-list {
            list-style: none;
            padding: 0 0.8rem;
            flex: 1;
            overflow-y: auto;
        }
        .nav-list li { margin-bottom: 0.25rem; }
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.65rem 0.85rem;
            border-radius: 12px;
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.88rem;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .nav-link:hover {
            background: var(--primary-50);
            color: var(--primary-700);
            transform: translateX(2px);
        }
        .nav-link.active {
            background: var(--primary-50);
            color: var(--primary-700);
            font-weight: 700;
            border-left: 3.5px solid var(--primary-500);
        }
        .nav-link .nav-icon {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
            transition: background 0.2s;
        }
        .nav-link.active .nav-icon {
            background: rgba(21, 156, 108, 0.15);
        }

        .sidebar-footer {
            border-top: 1px solid var(--border-subtle);
            padding: 1.1rem 1.1rem 0.9rem;
            background: #fff;
        }
        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 0.75rem;
            border-radius: 12px;
            background: #f8fafc;
            border: 1px solid var(--border-subtle);
            margin-bottom: 0.75rem;
        }
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary-700) 0%, var(--primary-500) 100%);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            font-weight: 800;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(16, 111, 78, 0.2);
        }
        .user-info-side .uname {
            font-size: 0.86rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.2;
        }
        .user-info-side .uemail {
            font-size: 0.72rem;
            color: var(--text-muted);
            margin-top: 1px;
        }
        .btn-logout-side {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.55rem 0.8rem;
            border-radius: 10px;
            background: #fff;
            border: 1.5px solid var(--border-subtle);
            cursor: pointer;
            font-family: inherit;
            font-size: 0.84rem;
            color: var(--text-muted);
            font-weight: 700;
            transition: all 0.2s ease;
        }
        .btn-logout-side:hover {
            background: #fff1f2;
            border-color: var(--accent-coral);
            color: var(--accent-coral);
        }

        /* ══════════ PANEL PRINCIPAL ══════════ */
        .main-panel {
            margin-left: 240px;
            flex: 1;
            min-height: 100vh;
            background: var(--bg-canvas);
            display: flex;
            flex-direction: column;
        }

        .page-header-bar {
            background: #ffffff;
            border-bottom: 1px solid var(--border-subtle);
            padding: 1.3rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top: 0; z-index: 40;
            box-shadow: 0 1px 4px rgba(15, 23, 42, 0.02);
        }
        .page-header-bar h1 {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--text-dark);
            letter-spacing: -0.02em;
        }
        .page-header-bar .subtitle {
            font-size: 0.86rem;
            color: var(--text-muted);
            margin-top: 0.15rem;
            font-weight: 500;
        }

        .page-content {
            padding: 2.2rem 2.5rem;
            flex: 1;
        }

        /* ── Botones Estándar Compartidos ── */
        .btn-primary, .btn-purple {
            background: linear-gradient(135deg, var(--primary-700) 0%, var(--primary-500) 100%) !important;
            color: #fff !important;
            border: none !important;
            padding: 0.6rem 1.3rem !important;
            border-radius: 10px !important;
            font-family: inherit !important;
            font-size: 0.88rem !important;
            font-weight: 700 !important;
            cursor: pointer !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 0.45rem !important;
            box-shadow: var(--shadow-glow) !important;
            transition: all 0.25s ease !important;
            text-decoration: none !important;
        }
        .btn-primary:hover, .btn-purple:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 18px rgba(16, 111, 78, 0.35) !important;
            filter: brightness(1.06) !important;
        }

        .btn-secondary {
            background: #ffffff !important;
            color: var(--text-dark) !important;
            border: 1.5px solid var(--border-subtle) !important;
            padding: 0.55rem 1.1rem !important;
            border-radius: 10px !important;
            font-family: inherit !important;
            font-size: 0.87rem !important;
            font-weight: 700 !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            text-decoration: none !important;
        }
        .btn-secondary:hover {
            background: #f8fafc !important;
            border-color: #cbd5e1 !important;
        }

        /* ── Flash Messages ── */
        .flash-success {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            padding: 0.9rem 1.3rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.92rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            box-shadow: var(--shadow-sm);
        }
        .flash-error {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #9f1239;
            padding: 0.9rem 1.3rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.92rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            box-shadow: var(--shadow-sm);
        }
        .flash-close {
            margin-left: auto;
            background: none;
            border: none;
            cursor: pointer;
            font-size: 1.2rem;
            color: inherit;
            opacity: 0.6;
            transition: opacity 0.2s;
        }
        .flash-close:hover { opacity: 1; }

        /* ── Stock Alert Styles ── */
        .sidebar-stock-badge {
            margin-left: auto;
            background: #ef4444;
            color: #ffffff;
            font-size: 0.72rem;
            font-weight: 800;
            padding: 0.15rem 0.55rem;
            border-radius: 20px;
            box-shadow: 0 2px 6px rgba(239, 68, 68, 0.4);
            animation: badge-pulse 2s infinite ease-in-out;
            line-height: 1.2;
        }
        @keyframes badge-pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .header-stock-alert {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            background: #fffbeb;
            border: 1.5px solid #fde68a;
            color: #b45309;
            padding: 0.4rem 0.85rem;
            border-radius: 20px;
            font-size: 0.82rem;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(217, 119, 6, 0.1);
        }
        .header-stock-alert:hover {
            background: #fef3c7;
            border-color: #f59e0b;
            transform: translateY(-1px);
            color: #92400e;
        }

        .flash-warning {
            background: #fffbeb;
            border: 1px solid #fde68a;
            color: #92400e;
            padding: 0.9rem 1.3rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-size: 0.92rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            box-shadow: var(--shadow-sm);
        }

        .stock-banner-alert {
            background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
            border: 1.5px solid #fcd34d;
            border-radius: 16px;
            padding: 1rem 1.3rem;
            margin-bottom: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.2rem;
            box-shadow: 0 4px 12px rgba(217, 119, 6, 0.1);
            position: relative;
            animation: fadeInDown 0.3s ease-out;
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .sba-left {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
        }
        .sba-icon-bubble {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #fef08a;
            border: 1px solid #fde047;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.35rem;
            flex-shrink: 0;
            box-shadow: 0 2px 6px rgba(217, 119, 6, 0.15);
        }
        .sba-title {
            color: #78350f;
            font-size: 0.92rem;
            font-weight: 500;
            line-height: 1.35;
        }
        .sba-title strong {
            color: #b45309;
            font-weight: 800;
            font-size: 0.96rem;
            display: block;
            margin-bottom: 0.15rem;
        }
        .sba-list {
            display: flex;
            flex-wrap: wrap;
            gap: 0.4rem;
            margin-top: 0.45rem;
        }
        .sba-pill {
            font-size: 0.76rem;
            font-weight: 700;
            background: #ffffff;
            color: #b45309;
            border: 1px solid #fcd34d;
            padding: 0.2rem 0.55rem;
            border-radius: 8px;
        }
        .sba-pill-out {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fca5a5;
        }
        .sba-more {
            font-size: 0.74rem;
            font-weight: 700;
            color: #92400e;
            align-self: center;
        }
        .sba-right {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            flex-shrink: 0;
        }
        .btn-sba-go {
            background: #d97706;
            color: #ffffff !important;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-size: 0.82rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s;
            box-shadow: 0 2px 6px rgba(217, 119, 6, 0.3);
            white-space: nowrap;
        }
        .btn-sba-go:hover {
            background: #b45309;
            transform: translateY(-1px);
        }
        .sba-dismiss {
            background: none;
            border: none;
            font-size: 1.15rem;
            color: #92400e;
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.2s;
            padding: 0.2rem 0.4rem;
        }
        .sba-dismiss:hover { opacity: 1; }

        @media (max-width: 1024px) {
            .sidebar { width: 80px; min-width: 80px; }
            .sidebar-logo .name, .sidebar-logo .role-tag, .menu-label, .user-info-side, .btn-logout-side span, .sidebar-stock-badge { display: none; }
            .nav-link { justify-content: center; padding: 0.75rem; }
            .main-panel { margin-left: 80px; }
            .page-header-bar { padding: 1.2rem 1.5rem; }
            .page-content { padding: 1.5rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

@php
    $activeMenu = $activeMenu ?? \Illuminate\Support\Facades\Route::currentRouteName();
    $productosAlertaStock = \App\Models\Producto::where('estado', 1)
        ->whereColumn('stock_actual', '<=', 'stock_minimo')
        ->orderBy('stock_actual', 'asc')
        ->get();
    $conteoStockBajo = $productosAlertaStock->count();
@endphp

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <a href="{{ route('dashboard') }}">
            <div class="icon">🛒</div>
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

    <div class="menu-label">Menú de Control</div>

    <ul class="nav-list">
        <li>
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ Str::startsWith($activeMenu, 'dashboard') ? 'active' : '' }}">
                <span class="nav-icon">🏠</span>
                <span>@if(Auth::user()->rol === 'empleado') Inicio @else Dashboard @endif</span>
            </a>
        </li>
        @if(Auth::user()->rol === 'administrador')
        <li>
            <a href="{{ route('usuarios.index') }}"
               class="nav-link {{ Str::startsWith($activeMenu, 'usuarios') ? 'active' : '' }}">
                <span class="nav-icon">👥</span>
                <span>Usuarios</span>
            </a>
        </li>
        <li>
            <a href="{{ route('categorias.index') }}"
               class="nav-link {{ Str::startsWith($activeMenu, 'categorias') ? 'active' : '' }}">
                <span class="nav-icon">🏷️</span>
                <span>Categorías</span>
            </a>
        </li>
        @endif
        <li>
            <a href="{{ route('productos.index') }}"
               class="nav-link {{ Str::startsWith($activeMenu, 'productos') ? 'active' : '' }}">
                <span class="nav-icon">📦</span>
                <span>Productos</span>
            </a>
        </li>
        <li>
            <a href="{{ route('inventario.index') }}"
               class="nav-link {{ Str::startsWith($activeMenu, 'inventario') ? 'active' : '' }}">
                <span class="nav-icon">🏗️</span>
                <span>Inventario</span>
                @if($conteoStockBajo > 0)
                    <span class="sidebar-stock-badge" title="{{ $conteoStockBajo }} producto(s) con stock bajo">⚠️ {{ $conteoStockBajo }}</span>
                @endif
            </a>
        </li>
        @if(Auth::user()->rol === 'administrador')
        <li>
            <a href="{{ route('ventas.index') }}"
               class="nav-link {{ Str::startsWith($activeMenu, 'ventas') ? 'active' : '' }}">
                <span class="nav-icon">🛒</span>
                <span>Ventas</span>
            </a>
        </li>
        <li>
            <a href="{{ route('proveedores.index') }}"
               class="nav-link {{ Str::startsWith($activeMenu, 'proveedores') ? 'active' : '' }}">
                <span class="nav-icon">🚚</span>
                <span>Proveedores</span>
            </a>
        </li>
        @endif
        <li>
            <a href="{{ route('reportes.index') }}"
               class="nav-link {{ Str::startsWith($activeMenu, 'reportes') ? 'active' : '' }}">
                <span class="nav-icon">📊</span>
                <span>Reportes</span>
            </a>
        </li>
        <li style="margin-top: 1rem; border-top: 1px dashed var(--border-subtle); padding-top: 0.8rem;">
            <a href="{{ route('index') }}" class="nav-link" target="_blank" title="Ver Tienda Online">
                <span class="nav-icon">🌐</span>
                <span>Ver Tienda</span>
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
            <button type="submit" class="btn-logout-side">
                <span>🚪</span>
                <span>Cerrar Sesión</span>
            </button>
        </form>
    </div>
</aside>

<!-- MAIN PANEL -->
<main class="main-panel">
    <!-- Page Header Bar -->
    <div class="page-header-bar">
        <div>
            <h1>@yield('page-title', 'Panel')</h1>
            <div class="subtitle">@yield('page-subtitle')</div>
        </div>
        <div style="display:flex;align-items:center;gap:.8rem;">
            @if($conteoStockBajo > 0)
            <a href="{{ route('inventario.index') }}" class="header-stock-alert" title="Ver {{ $conteoStockBajo }} productos con stock bajo">
                <span>⚠️</span>
                <span><b>{{ $conteoStockBajo }}</b> Stock Bajo</span>
            </a>
            @endif
            <div>@yield('page-action')</div>
        </div>
    </div>

    <!-- Content -->
    <div class="page-content">
        @if(session('success'))
        <div class="flash-success" id="flash-ok">
            <span>✅</span>
            <span>{{ session('success') }}</span>
            <button class="flash-close" onclick="document.getElementById('flash-ok').remove()">✕</button>
        </div>
        @endif
        @if(session('warning'))
        <div class="flash-warning" id="flash-warn">
            <span style="font-size:1.2rem;">⚠️</span>
            <div style="flex:1;">{!! session('warning') !!}</div>
            <button class="flash-close" onclick="document.getElementById('flash-warn').remove()">✕</button>
        </div>
        @endif
        @if(session('error'))
        <div class="flash-error" id="flash-err">
            <span>❌</span>
            <span>{{ session('error') }}</span>
            <button class="flash-close" onclick="document.getElementById('flash-err').remove()">✕</button>
        </div>
        @endif

        {{-- Alerta Global de Stock Bajo si hay productos críticos (visible en módulos fuera de inventario) --}}
        @if($conteoStockBajo > 0 && !request()->routeIs('inventario.index'))
        <div class="stock-banner-alert" id="stockAlertBanner">
            <div class="sba-left">
                <div class="sba-icon-bubble">⚠️</div>
                <div>
                    <div class="sba-title">
                        <strong>¡Alerta de Inventario: Stock Bajo!</strong>
                        <span>Hay <b>{{ $conteoStockBajo }}</b> {{ $conteoStockBajo == 1 ? 'producto' : 'productos' }} con existencias en nivel crítico o por debajo del mínimo:</span>
                    </div>
                    <div class="sba-list">
                        @foreach($productosAlertaStock->take(4) as $pa)
                            <span class="sba-pill {{ $pa->stock_actual == 0 ? 'sba-pill-out' : '' }}">
                                {{ $pa->nombre }}: <b>{{ $pa->stock_actual }}</b> / mín. {{ $pa->stock_minimo }}
                            </span>
                        @endforeach
                        @if($conteoStockBajo > 4)
                            <span class="sba-more">+{{ $conteoStockBajo - 4 }} más</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="sba-right">
                <a href="{{ route('inventario.index') }}" class="btn-sba-go">Gestionar Inventario →</a>
                <button type="button" class="sba-dismiss" onclick="document.getElementById('stockAlertBanner').remove()" title="Cerrar aviso">✕</button>
            </div>
        </div>
        @endif

        @yield('content')
    </div>
</main>

@stack('scripts')
</body>
</html>
