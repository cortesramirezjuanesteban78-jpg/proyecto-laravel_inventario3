<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Panel Empleado — SuperFresco</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f0f4f0; color: #1a202c; display: flex; min-height: 100vh; }

        /* ══════════ SIDEBAR ══════════ */
        .sidebar {
            width: 200px; min-width: 200px; background: #fff;
            border-right: 1px solid #e8ede8;
            display: flex; flex-direction: column;
            position: fixed; top: 0; left: 0; bottom: 0; z-index: 50;
        }
        .sidebar-logo {
            padding: 1.3rem 1.3rem .9rem;
            border-bottom: 1px solid #f0f4f0;
        }
        .sidebar-logo a { display: flex; align-items: center; gap: .5rem; text-decoration: none; }
        .sidebar-logo .s-icon { font-size: 1.4rem; }
        .sidebar-logo .s-name { font-size: 1rem; font-weight: 400; color: #111827; }
        .sidebar-logo .s-name b { color: #16a34a; font-weight: 800; }
        .sidebar-logo .role-tag { font-size: .7rem; color: #9ca3af; margin-top: .15rem; }

        .menu-label {
            font-size: .65rem; font-weight: 700; letter-spacing: .1em;
            text-transform: uppercase; color: #9ca3af;
            padding: 1.1rem 1.3rem .45rem;
        }
        .nav-list { list-style: none; padding: 0 .6rem; flex: 1; }
        .nav-list li { margin-bottom: .1rem; }
        .nav-link {
            display: flex; align-items: center; gap: .7rem;
            padding: .55rem .7rem; border-radius: 10px;
            text-decoration: none; color: #4b5563;
            font-size: .86rem; font-weight: 500; transition: all .18s;
        }
        .nav-link:hover { background: #f0fdf4; color: #16a34a; }
        .nav-link.active { background: #dbeafe; color: #1d4ed8; font-weight: 700; }
        .nav-link .nav-icon {
            width: 28px; height: 28px; border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            font-size: .88rem; flex-shrink: 0; background: transparent;
            transition: background .18s;
        }
        .nav-link.active .nav-icon { background: transparent; }

        /* Footer */
        .sidebar-footer { border-top: 1px solid #f0f4f0; padding: .9rem 1rem .8rem; }
        .user-profile {
            display: flex; align-items: center; gap: .6rem;
            padding: .5rem .65rem; border-radius: 10px;
            background: #f9fafb; margin-bottom: .6rem;
        }
        .user-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: #dbeafe; color: #1d4ed8;
            display: flex; align-items: center; justify-content: center;
            font-size: .88rem; font-weight: 800; flex-shrink: 0;
        }
        .uname  { font-size: .83rem; font-weight: 700; color: #111827; line-height: 1.2; }
        .urole  { font-size: .7rem; color: #9ca3af; text-transform: capitalize; }
        .btn-logout {
            width: 100%; display: flex; align-items: center; gap: .5rem;
            padding: .45rem .65rem; border-radius: 8px;
            background: none; border: none; cursor: pointer;
            font-family: inherit; font-size: .82rem;
            color: #ef4444; font-weight: 600; transition: background .18s;
        }
        .btn-logout:hover { background: #fff5f5; }

        /* ══════════ MAIN ══════════ */
        .main-panel {
            margin-left: 200px; flex: 1;
            padding: 2rem 2.4rem; min-height: 100vh;
        }

        /* Header */
        .page-header {
            display: flex; align-items: flex-start;
            justify-content: space-between; margin-bottom: 1.8rem;
        }
        .page-header h1 { font-size: 1.6rem; font-weight: 800; color: #111827; }
        .page-header .sub {
            font-size: .83rem; color: #9ca3af; margin-top: .25rem;
        }
        .status-badge {
            display: flex; align-items: center; gap: .4rem;
            background: #fff; border: 1px solid #e5e7eb;
            padding: .38rem .9rem; border-radius: 20px;
            font-size: .8rem; font-weight: 600; color: #374151;
        }
        .status-dot {
            width: 8px; height: 8px; border-radius: 50%; background: #16a34a;
            animation: pulse 2s infinite;
        }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.35} }

        /* Stats grid */
        .stats-grid {
            display: grid; grid-template-columns: repeat(4, 1fr);
            gap: 1rem; margin-bottom: 2rem;
        }
        .stat-card {
            background: #fff; border-radius: 14px;
            padding: 1.1rem 1.3rem;
            box-shadow: 0 1px 8px rgba(0,0,0,.06);
            display: flex; align-items: center; gap: .9rem;
            border: 1px solid #f3f4f6;
            transition: box-shadow .2s;
        }
        .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.1); }
        .sc-icon-wrap {
            width: 42px; height: 42px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; flex-shrink: 0;
        }
        .sc-blue   { background: #dbeafe; }
        .sc-green  { background: #dcfce7; }
        .sc-yellow { background: #fef9c3; }
        .sc-purple { background: #ede9fe; }
        .sc-num   { font-size: 1.5rem; font-weight: 900; color: #111827; line-height: 1; }
        .sc-label { font-size: .8rem; color: #6b7280; margin-top: .15rem; }

        /* Tools section */
        .section-label {
            font-size: .7rem; font-weight: 700; letter-spacing: .1em;
            text-transform: uppercase; color: #9ca3af; margin-bottom: .9rem;
        }
        .tools-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem;
        }
        .tool-card {
            background: #fff; border-radius: 14px;
            padding: 1.1rem 1.3rem;
            display: flex; align-items: center; gap: .9rem;
            text-decoration: none; color: #1a202c;
            border: 1px solid #f3f4f6;
            box-shadow: 0 1px 8px rgba(0,0,0,.05);
            transition: all .2s;
        }
        .tool-card:hover {
            box-shadow: 0 6px 20px rgba(0,0,0,.09);
            transform: translateY(-2px);
        }
        .tc-icon {
            width: 42px; height: 42px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem; flex-shrink: 0;
        }
        .tc-blue   { background: #dbeafe; }
        .tc-green  { background: #dcfce7; }
        .tc-purple { background: #ede9fe; }
        .tc-text .tc-title { font-size: .9rem; font-weight: 700; color: #111827; }
        .tc-text .tc-sub   { font-size: .76rem; color: #9ca3af; margin-top: .1rem; }
        .tc-arrow {
            margin-left: auto; color: #d1d5db; font-size: 1.1rem;
            flex-shrink: 0; transition: color .2s, transform .2s;
        }
        .tool-card:hover .tc-arrow { color: #16a34a; transform: translateX(3px); }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <a href="{{ route('dashboard') }}">
            <span class="s-icon">🛒</span>
            <div>
                <div class="s-name">Super<b>Fresco</b></div>
                <div class="role-tag">Panel Empleado</div>
            </div>
        </a>
    </div>

    <div class="menu-label">Menú</div>

    <ul class="nav-list">
        <li>
            <a href="{{ route('dashboard') }}" class="nav-link active">
                <span class="nav-icon">🏠</span> Inicio
            </a>
        </li>
        <li>
            <a href="{{ route('productos.index') }}" class="nav-link">
                <span class="nav-icon">📦</span> Productos
            </a>
        </li>
        <li>
            <a href="{{ route('inventario.index') }}" class="nav-link">
                <span class="nav-icon">🏗️</span> Inventario
            </a>
        </li>
        <li>
            <a href="{{ route('reportes.index') }}" class="nav-link">
                <span class="nav-icon">📊</span> Reportes
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <div class="user-profile">
            <div class="user-avatar">{{ strtoupper(substr(Auth::user()->nombres, 0, 1)) }}</div>
            <div>
                <div class="uname">{{ Auth::user()->nombres }}</div>
                <div class="urole">Empleado</div>
            </div>
        </div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <span>↪</span> Cerrar Sesión
            </button>
        </form>
    </div>
</aside>

<!-- MAIN -->
<main class="main-panel">

    <!-- Header -->
    <div class="page-header">
        <div>
            <h1>¡Hola, {{ Auth::user()->nombres }}! 👋</h1>
            <p class="sub">
                {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                &nbsp;·&nbsp; Panel de Empleado
            </p>
        </div>
        <div class="status-badge">
            <span class="status-dot"></span> Todo en orden
        </div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="sc-icon-wrap sc-blue">📦</div>
            <div>
                <div class="sc-num">{{ $totalProductos }}</div>
                <div class="sc-label">Productos</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="sc-icon-wrap sc-green">✅</div>
            <div>
                <div class="sc-num">{{ $productosActivos }}</div>
                <div class="sc-label">Activos</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="sc-icon-wrap sc-yellow">⚠️</div>
            <div>
                <div class="sc-num">{{ $stockBajo }}</div>
                <div class="sc-label">Stock Bajo</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="sc-icon-wrap sc-purple">💲</div>
            <div>
                <div class="sc-num">${{ number_format($valorInventario, 0, ',', '.') }}</div>
                <div class="sc-label">Valor Inventario</div>
            </div>
        </div>
    </div>

    <!-- Herramientas -->
    <div class="section-label">Mis Herramientas</div>
    <div class="tools-grid">

        <a href="{{ route('productos.index') }}" class="tool-card">
            <div class="tc-icon tc-blue">📦</div>
            <div class="tc-text">
                <div class="tc-title">Productos</div>
                <div class="tc-sub">Ver y consultar el catálogo</div>
            </div>
            <span class="tc-arrow">›</span>
        </a>

        <a href="{{ route('inventario.index') }}" class="tool-card">
            <div class="tc-icon tc-green">🏗️</div>
            <div class="tc-text">
                <div class="tc-title">Inventario</div>
                <div class="tc-sub">Registrar entradas y salidas</div>
            </div>
            <span class="tc-arrow">›</span>
        </a>

        <a href="{{ route('reportes.index') }}" class="tool-card">
            <div class="tc-icon tc-purple">📊</div>
            <div class="tc-text">
                <div class="tc-title">Reportes</div>
                <div class="tc-sub">Ver estadísticas y stock bajo</div>
            </div>
            <span class="tc-arrow">›</span>
        </a>

    </div>

</main>

</body>
</html>
