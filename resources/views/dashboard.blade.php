@extends('layouts.sidebar')

@section('title', 'Dashboard')
@section('page-title', '¡Hola, ' . Auth::user()->nombres . '! 👋')
@section('page-subtitle', now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') . ' · Panel de Control Principal')

@section('page-action')
<div style="display:flex;align-items:center;gap:.6rem;background:#fff;border:1px solid var(--border-subtle);padding:.45rem 1rem;border-radius:24px;font-size:.84rem;font-weight:700;color:#334155;box-shadow:var(--shadow-sm);">
    <span style="width:8px;height:8px;border-radius:50%;background:#159c6c;display:inline-block;animation:pulse-dot 2s infinite;"></span>
    Sistema en Línea
</div>
<style>@keyframes pulse-dot{0%,100%{opacity:1;}50%{opacity:.35;}}</style>
@endsection

@push('styles')
<style>
    /* ── STATS CARDS ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.3rem;
        margin-bottom: 2.4rem;
    }
    .stat-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 1.5rem 1.6rem 1.3rem;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
        transition: all 0.25s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
    }
    .stat-card .sc-icon {
        font-size: 1.6rem;
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.9rem;
    }
    
    .stat-card.emerald { border-left: 4px solid #106f4e; }
    .stat-card.emerald .sc-icon { background: #ecfdf5; border: 1px solid #d1fae5; }

    .stat-card.blue { border-left: 4px solid #2563eb; }
    .stat-card.blue .sc-icon { background: #eff6ff; border: 1px solid #dbeafe; }

    .stat-card.gold { border-left: 4px solid #d97706; }
    .stat-card.gold .sc-icon { background: #fffbeb; border: 1px solid #fef3c7; }

    .stat-card.coral { border-left: 4px solid #f43f5e; }
    .stat-card.coral .sc-icon { background: #fff1f2; border: 1px solid #fecdd3; }

    .stat-card .sc-num {
        font-size: 2.2rem;
        font-weight: 900;
        color: var(--text-dark);
        line-height: 1;
        margin-bottom: 0.35rem;
        letter-spacing: -0.5px;
    }
    .stat-card .sc-label {
        font-size: 0.92rem;
        font-weight: 700;
        color: var(--text-dark);
    }
    .stat-card .sc-sub {
        font-size: 0.78rem;
        color: var(--text-muted);
        font-weight: 500;
        margin-top: 0.15rem;
    }
    .stat-card .sc-bg {
        position: absolute;
        right: -10px;
        bottom: -15px;
        font-size: 5rem;
        opacity: 0.05;
        pointer-events: none;
    }

    /* ── ACCESOS RÁPIDOS ── */
    .section-title {
        font-size: 0.8rem;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 1.2rem;
    }
    .quick-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.2rem;
    }
    .quick-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.25rem 1.4rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        text-decoration: none;
        color: var(--text-dark);
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
        transition: all 0.25s ease;
        position: relative;
    }
    .quick-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-3px);
        border-color: rgba(16, 111, 78, 0.3);
    }
    .qc-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }
    .qc-green  { background: #ecfdf5; border: 1px solid #d1fae5; }
    .qc-blue   { background: #eff6ff; border: 1px solid #dbeafe; }
    .qc-purple { background: #f5f3ff; border: 1px solid #ede9fe; }
    .qc-orange { background: #fff7ed; border: 1px solid #ffedd5; }
    .qc-teal   { background: #f0fdfa; border: 1px solid #ccfbf1; }
    .qc-pink   { background: #fff1f2; border: 1px solid #fecdd3; }

    .qc-text .qt-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-dark);
    }
    .qc-text .qt-sub {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 0.15rem;
        font-weight: 500;
    }
    .qc-arrow {
        margin-left: auto;
        color: #cbd5e1;
        font-size: 1.2rem;
        font-weight: 700;
        flex-shrink: 0;
        transition: all 0.2s;
    }
    .quick-card:hover .qc-arrow {
        color: #159c6c;
        transform: translateX(4px);
    }

    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .quick-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) {
        .stats-grid { grid-template-columns: 1fr; }
        .quick-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card emerald">
        <div class="sc-icon">👥</div>
        <div class="sc-num">{{ $stats['usuarios'] }}</div>
        <div class="sc-label">Usuarios Activos</div>
        <div class="sc-sub">Cuentas registradas</div>
        <span class="sc-bg">👥</span>
    </div>
    <div class="stat-card blue">
        <div class="sc-icon">📦</div>
        <div class="sc-num">{{ $stats['productos'] }}</div>
        <div class="sc-label">Productos en Catálogo</div>
        <div class="sc-sub">Disponibles para venta</div>
        <span class="sc-bg">📦</span>
    </div>
    <div class="stat-card gold">
        <div class="sc-icon">🏷️</div>
        <div class="sc-num">{{ $stats['categorias'] }}</div>
        <div class="sc-label">Categorías Activas</div>
        <div class="sc-sub">Clasificación de tienda</div>
        <span class="sc-bg">🏷️</span>
    </div>
    <div class="stat-card coral">
        <div class="sc-icon">🚚</div>
        <div class="sc-num">{{ $stats['proveedores'] }}</div>
        <div class="sc-label">Proveedores Aliados</div>
        <div class="sc-sub">Cadena de suministros</div>
        <span class="sc-bg">🚚</span>
    </div>
</div>

<!-- Accesos Rápidos -->
<div class="section-title">Módulos de Gestión Rápida</div>
<div class="quick-grid">

    @if(Auth::user()->rol === 'administrador')
    <a href="{{ route('usuarios.index') }}" class="quick-card">
        <div class="qc-icon qc-green">👥</div>
        <div class="qc-text">
            <div class="qt-title">Gestión de Usuarios</div>
            <div class="qt-sub">Roles, permisos y cuentas</div>
        </div>
        <span class="qc-arrow">›</span>
    </a>
    @endif

    <a href="{{ route('categorias.index') }}" class="quick-card">
        <div class="qc-icon qc-blue">🏷️</div>
        <div class="qc-text">
            <div class="qt-title">Categorías</div>
            <div class="qt-sub">Familias de alimentos frescos</div>
        </div>
        <span class="qc-arrow">›</span>
    </a>

    <a href="{{ route('productos.index') }}" class="quick-card">
        <div class="qc-icon qc-purple">📦</div>
        <div class="qc-text">
            <div class="qt-title">Catálogo de Productos</div>
            <div class="qt-sub">Precios, stock e imágenes</div>
        </div>
        <span class="qc-arrow">›</span>
    </a>

    <a href="{{ route('inventario.index') }}" class="quick-card">
        <div class="qc-icon qc-orange">🏗️</div>
        <div class="qc-text">
            <div class="qt-title">Control de Inventario</div>
            <div class="qt-sub">Entradas, salidas y mermas</div>
        </div>
        <span class="qc-arrow">›</span>
    </a>

    @if(Auth::user()->rol === 'administrador')
    <a href="{{ route('ventas.index') }}" class="quick-card">
        <div class="qc-icon qc-teal">🛒</div>
        <div class="qc-text">
            <div class="qt-title">Historial de Ventas</div>
            <div class="qt-sub">Registro y facturación</div>
        </div>
        <span class="qc-arrow">›</span>
    </a>

    <a href="{{ route('proveedores.index') }}" class="quick-card">
        <div class="qc-icon qc-pink">🚚</div>
        <div class="qc-text">
            <div class="qt-title">Proveedores</div>
            <div class="qt-sub">Directorio y contactos</div>
        </div>
        <span class="qc-arrow">›</span>
    </a>
    @endif

    <a href="{{ route('reportes.index') }}" class="quick-card">
        <div class="qc-icon qc-teal">📊</div>
        <div class="qc-text">
            <div class="qt-title">Reportes & Estadísticas</div>
            <div class="qt-sub">Métricas de rendimiento y stock bajo</div>
        </div>
        <span class="qc-arrow">›</span>
    </a>

</div>

@endsection
