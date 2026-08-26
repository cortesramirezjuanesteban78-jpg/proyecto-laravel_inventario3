<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel — SuperFresco</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f4f0; color: #1a202c; display: flex; min-height: 100vh; }

        /* ══════════════════════════════
           SIDEBAR
        ══════════════════════════════ */
        .sidebar {
            width: 220px;
            min-width: 220px;
            background: #fff;
            border-right: 1px solid #e8ede8;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 50;
        }

        /* Logo */
        .sidebar-logo {
            padding: 1.4rem 1.4rem 1rem;
            border-bottom: 1px solid #f0f4f0;
        }
        .sidebar-logo a {
            display: flex; align-items: center; gap: .5rem; text-decoration: none;
        }
        .sidebar-logo .icon { font-size: 1.5rem; }
        .sidebar-logo .name { font-size: 1.1rem; font-weight: 400; color: #111827; }
        .sidebar-logo .name b { color: #16a34a; font-weight: 800; }
        .sidebar-logo .role-tag {
            font-size: .72rem; color: #6b7280; margin-top: .2rem;
            padding-left: .1rem;
        }

        /* Menú label */
        .menu-label {
            font-size: .68rem; font-weight: 700; letter-spacing: .1em;
            text-transform: uppercase; color: #9ca3af;
            padding: 1.2rem 1.4rem .5rem;
        }

        /* Nav links */
        .nav-list { list-style: none; padding: 0 .7rem; flex: 1; }
        .nav-list li { margin-bottom: .15rem; }
        .nav-link {
            display: flex; align-items: center; gap: .75rem;
            padding: .6rem .75rem; border-radius: 10px;
            text-decoration: none; color: #4b5563;
            font-size: .88rem; font-weight: 500;
            transition: all .18s;
        }
        .nav-link:hover { background: #f0fdf4; color: #16a34a; }
        .nav-link.active { background: #dcfce7; color: #15803d; font-weight: 700; }
        .nav-link .nav-icon {
            width: 30px; height: 30px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: .95rem; flex-shrink: 0; background: transparent;
            transition: background .18s;
        }
        .nav-link.active .nav-icon { background: #16a34a; }
        .nav-link:hover .nav-icon  { background: #bbf7d0; }

        /* Perfil abajo */
        .sidebar-footer {
            border-top: 1px solid #f0f4f0;
            padding: 1rem 1.2rem .8rem;
        }
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
            color: #ef4444; font-weight: 600;
            transition: background .18s;
        }
        .btn-logout-side:hover { background: #fff5f5; }

        /* ══════════════════════════════
           CONTENIDO PRINCIPAL
        ══════════════════════════════ */
        .main-panel {
            margin-left: 220px;
            flex: 1;
            padding: 2rem 2.2rem;
            min-height: 100vh;
        }

        /* Cabecera */
        .page-header {
            display: flex; align-items: flex-start;
            justify-content: space-between; margin-bottom: 1.8rem;
        }
        .page-header h1 { font-size: 1.65rem; font-weight: 800; color: #111827; }
        .page-header .date { font-size: .85rem; color: #9ca3af; margin-top: .2rem; }
        .status-badge {
            display: flex; align-items: center; gap: .4rem;
            background: #fff; border: 1px solid #e5e7eb;
            padding: .4rem .9rem; border-radius: 20px;
            font-size: .82rem; font-weight: 600; color: #374151;
        }
        .status-dot { width: 8px; height: 8px; border-radius: 50%; background: #16a34a; animation: pulse-dot 2s infinite; }
        @keyframes pulse-dot { 0%,100%{opacity:1;} 50%{opacity:.4;} }

        /* ── STATS CARDS ── */
        .stats-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.2rem;
            margin-bottom: 2rem;
        }
        .stat-card {
            border-radius: 16px; padding: 1.4rem 1.5rem 1.2rem;
            position: relative; overflow: hidden; cursor: default;
            transition: transform .2s, box-shadow .2s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 8px 24px rgba(0,0,0,.1); }
        .stat-card.green  { background: #d1fae5; }
        .stat-card.blue   { background: #dbeafe; }
        .stat-card.purple { background: #ede9fe; }
        .stat-card.orange { background: #ffedd5; }
        .stat-card .sc-icon {
            font-size: 1.6rem; margin-bottom: .6rem;
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
        }
        .stat-card.green  .sc-icon { background: rgba(22,163,74,.15); }
        .stat-card.blue   .sc-icon { background: rgba(59,130,246,.15); }
        .stat-card.purple .sc-icon { background: rgba(124,58,237,.15); }
        .stat-card.orange .sc-icon { background: rgba(234,88,12,.15); }
        .stat-card .sc-num  { font-size: 2rem; font-weight: 900; color: #111827; line-height: 1; margin-bottom: .2rem; }
        .stat-card .sc-label { font-size: .9rem; font-weight: 700; color: #1a202c; }
        .stat-card .sc-sub   { font-size: .78rem; color: #6b7280; margin-top: .1rem; }
        .stat-card .sc-bg {
            position: absolute; right: -20px; bottom: -20px;
            font-size: 5rem; opacity: .08; pointer-events: none;
        }

        /* ── ACCESOS RÁPIDOS ── */
        .section-title {
            font-size: .75rem; font-weight: 700; letter-spacing: .1em;
            text-transform: uppercase; color: #6b7280; margin-bottom: 1rem;
        }
        .quick-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;
        }
        .quick-card {
            background: #fff; border-radius: 14px;
            padding: 1.1rem 1.2rem; display: flex;
            align-items: center; gap: .9rem;
            text-decoration: none; color: #1a202c;
            border: 1px solid #f0f0f0;
            transition: all .2s; position: relative;
        }
        .quick-card:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,.08);
            transform: translateY(-2px); border-color: #e0e0e0;
        }
        .qc-icon {
            width: 44px; height: 44px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; flex-shrink: 0;
        }
        .qc-green  { background: #dcfce7; }
        .qc-blue   { background: #dbeafe; }
        .qc-purple { background: #ede9fe; }
        .qc-orange { background: #ffedd5; }
        .qc-teal   { background: #ccfbf1; }
        .qc-pink   { background: #fce7f3; }
        .qc-text .qt-title { font-size: .9rem; font-weight: 700; color: #111827; }
        .qc-text .qt-sub   { font-size: .78rem; color: #9ca3af; margin-top: .1rem; }
        .qc-arrow {
            margin-left: auto; color: #d1d5db; font-size: 1.1rem;
            flex-shrink: 0; transition: color .2s, transform .2s;
        }
        .quick-card:hover .qc-arrow { color: #16a34a; transform: translateX(3px); }
    </style>
</head>
<body>

<!-- ════════════════ SIDEBAR ════════════════ -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <a href="{{ route('index') }}">
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
            <a href="{{ route('dashboard') }}" class="nav-link active">
                <span class="nav-icon">🏠</span> Dashboard
            </a>
        </li>
        @if(Auth::user()->rol === 'administrador')
        <li>
            <a href="{{ route('usuarios.index') }}" class="nav-link">
                <span class="nav-icon">👥</span> Usuarios
            </a>
        </li>
        @endif
        <li><a href="{{ route('categorias.index') }}" class="nav-link"><span class="nav-icon">🏷️</span> Categorías</a></li>
        <li><a href="{{ route('productos.index') }}" class="nav-link"><span class="nav-icon">📦</span> Productos</a></li>
        <li><a href="{{ route('inventario.index') }}" class="nav-link"><span class="nav-icon">🏗️</span> Inventario</a></li>
        <li><a href="{{ route('ventas.index') }}" class="nav-link"><span class="nav-icon">🛒</span> Ventas</a></li>
        <li><a href="{{ route('proveedores.index') }}" class="nav-link"><span class="nav-icon">🚚</span> Proveedores</a></li>
        <li><a href="{{ route('reportes.index') }}" class="nav-link"><span class="nav-icon">📊</span> Reportes</a></li>
    </ul>

    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->nombres, 0, 1)) }}</div>
            <div class="user-info-side">
                <div class="uname">{{ Auth::user()->nombres }}</div>
                <div class="uemail">{{ Auth::user()->email }}</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout-side">
                🚪 Cerrar Sesión
            </button>
        </form>
    </div>
</aside>

<!-- ════════════════ CONTENIDO ════════════════ -->
<main class="main-panel">

    <!-- Cabecera -->
    <div class="page-header">
        <div>
            <h1>¡Hola, {{ Auth::user()->nombres }}! 👋</h1>
            <p class="date">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
        </div>
        <div class="status-badge">
            <span class="status-dot"></span> Sistema activo
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card green">
            <div class="sc-icon">👥</div>
            <div class="sc-num">{{ $stats['usuarios'] }}</div>
            <div class="sc-label">Usuarios</div>
            <div class="sc-sub">registrados</div>
            <span class="sc-bg">👥</span>
        </div>
        <div class="stat-card blue">
            <div class="sc-icon">📦</div>
            <div class="sc-num">{{ $stats['productos'] }}</div>
            <div class="sc-label">Productos</div>
            <div class="sc-sub">en catálogo</div>
            <span class="sc-bg">📦</span>
        </div>
        <div class="stat-card purple">
            <div class="sc-icon">🏷️</div>
            <div class="sc-num">{{ $stats['categorias'] }}</div>
            <div class="sc-label">Categorías</div>
            <div class="sc-sub">activas</div>
            <span class="sc-bg">🏷️</span>
        </div>
        <div class="stat-card orange">
            <div class="sc-icon">🚚</div>
            <div class="sc-num">{{ $stats['proveedores'] }}</div>
            <div class="sc-label">Proveedores</div>
            <div class="sc-sub">activos</div>
            <span class="sc-bg">🚚</span>
        </div>
    </div>

    <!-- Accesos rápidos -->
    <div class="section-title">Accesos rápidos</div>
    <div class="quick-grid">

        @if(Auth::user()->rol === 'administrador')
        <a href="{{ route('usuarios.index') }}" class="quick-card">
            <div class="qc-icon qc-green">👥</div>
            <div class="qc-text">
                <div class="qt-title">Usuarios</div>
                <div class="qt-sub">Ver y gestionar cuentas</div>
            </div>
            <span class="qc-arrow">›</span>
        </a>
        @endif

        <a href="{{ route('categorias.index') }}" class="quick-card">
            <div class="qc-icon qc-blue">🏷️</div>
            <div class="qc-text">
                <div class="qt-title">Categorías</div>
                <div class="qt-sub">Organiza tu catálogo</div>
            </div>
            <span class="qc-arrow">›</span>
        </a>

        <a href="{{ route('productos.index') }}" class="quick-card">
            <div class="qc-icon qc-purple">📦</div>
            <div class="qc-text">
                <div class="qt-title">Productos</div>
                <div class="qt-sub">Gestión de productos</div>
            </div>
            <span class="qc-arrow">›</span>
        </a>

        <a href="{{ route('inventario.index') }}" class="quick-card">
            <div class="qc-icon qc-orange">🏗️</div>
            <div class="qc-text">
                <div class="qt-title">Inventario</div>
                <div class="qt-sub">Entradas y salidas de stock</div>
            </div>
            <span class="qc-arrow">›</span>
        </a>

        <a href="{{ route('proveedores.index') }}" class="quick-card">
            <div class="qc-icon qc-teal">🚚</div>
            <div class="qc-text">
                <div class="qt-title">Proveedores</div>
                <div class="qt-sub">Gestión de proveedores</div>
            </div>
            <span class="qc-arrow">›</span>
        </a>

        <a href="{{ route('reportes.index') }}" class="quick-card">
            <div class="qc-icon qc-pink">📊</div>
            <div class="qc-text">
                <div class="qt-title">Reportes</div>
                <div class="qt-sub">Estadísticas y análisis</div>
            </div>
            <span class="qc-arrow">›</span>
        </a>

    </div>

</main>

</body>
</html>
