@extends('layouts.sidebar')

@section('title', 'Panel Empleado')
@section('page-title', '¡Hola, ' . Auth::user()->nombres . '! 👋')
@section('page-subtitle', now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') . ' · Panel Operativo de Empleado')

@section('page-action')
<div style="display:flex;align-items:center;gap:.6rem;background:#fff;border:1px solid var(--border-subtle);padding:.45rem 1rem;border-radius:24px;font-size:.84rem;font-weight:700;color:#334155;box-shadow:var(--shadow-sm);">
    <span style="width:8px;height:8px;border-radius:50%;background:#159c6c;display:inline-block;animation:pulse-dot 2s infinite;"></span>
    Turno Activo
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
        padding: 1.4rem 1.5rem;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.25s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
    .sc-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .sc-blue   { background: #eff6ff; border: 1px solid #dbeafe; }
    .sc-green  { background: #ecfdf5; border: 1px solid #d1fae5; }
    .sc-yellow { background: #fffbeb; border: 1px solid #fef3c7; }
    .sc-purple { background: #f5f3ff; border: 1px solid #ede9fe; }

    .sc-num {
        font-size: 1.8rem;
        font-weight: 900;
        color: var(--text-dark);
        line-height: 1.1;
        letter-spacing: -0.5px;
    }
    .sc-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-top: 0.2rem;
    }

    /* ── HERRAMIENTAS ── */
    .section-label {
        font-size: 0.8rem;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: var(--text-muted);
        margin-bottom: 1.2rem;
    }
    .tools-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.2rem;
    }
    .tool-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 1.3rem 1.4rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        text-decoration: none;
        color: var(--text-dark);
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm);
        transition: all 0.25s ease;
    }
    .tool-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-3px);
        border-color: rgba(16, 111, 78, 0.3);
    }
    .tc-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
        flex-shrink: 0;
    }
    .tc-blue   { background: #eff6ff; border: 1px solid #dbeafe; }
    .tc-green  { background: #ecfdf5; border: 1px solid #d1fae5; }
    .tc-purple { background: #f5f3ff; border: 1px solid #ede9fe; }

    .tc-text .tc-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-dark);
    }
    .tc-text .tc-sub {
        font-size: 0.8rem;
        color: var(--text-muted);
        margin-top: 0.15rem;
        font-weight: 500;
    }
    .tc-arrow {
        margin-left: auto;
        color: #cbd5e1;
        font-size: 1.2rem;
        font-weight: 700;
        flex-shrink: 0;
        transition: all 0.2s;
    }
    .tool-card:hover .tc-arrow {
        color: #159c6c;
        transform: translateX(4px);
    }

    @media (max-width: 1100px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .tools-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 640px) {
        .stats-grid { grid-template-columns: 1fr; }
        .tools-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="sc-icon-wrap sc-blue">📦</div>
        <div>
            <div class="sc-num">{{ $totalProductos }}</div>
            <div class="sc-label">Total Productos</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="sc-icon-wrap sc-green">✅</div>
        <div>
            <div class="sc-num">{{ $productosActivos }}</div>
            <div class="sc-label">Productos Activos</div>
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
            <div class="sc-label">Valor en Inventario</div>
        </div>
    </div>
</div>

<!-- Herramientas -->
<div class="section-label">Operaciones & Herramientas</div>
<div class="tools-grid">

    <a href="{{ route('productos.index') }}" class="tool-card">
        <div class="tc-icon tc-blue">📦</div>
        <div class="tc-text">
            <div class="tc-title">Catálogo de Productos</div>
            <div class="tc-sub">Consultar existencias y precios</div>
        </div>
        <span class="tc-arrow">›</span>
    </a>

    <a href="{{ route('inventario.index') }}" class="tool-card">
        <div class="tc-icon tc-green">🏗️</div>
        <div class="tc-text">
            <div class="tc-title">Inventario en Vivo</div>
            <div class="tc-sub">Registrar entradas y salidas de stock</div>
        </div>
        <span class="tc-arrow">›</span>
    </a>

    <a href="{{ route('reportes.index') }}" class="tool-card">
        <div class="tc-icon tc-purple">📊</div>
        <div class="tc-text">
            <div class="tc-title">Reportes Operativos</div>
            <div class="tc-sub">Visualizar alertas y stock crítico</div>
        </div>
        <span class="tc-arrow">›</span>
    </a>

</div>

@endsection
