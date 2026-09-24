@extends('layouts.sidebar')

@section('title', 'Reportes')
@section('page-title', 'Reportes')
@section('page-subtitle', 'Estadísticas y análisis del negocio')

@section('page-action')
<div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;">
    <form method="GET" action="{{ route('reportes.index') }}" style="display:flex;align-items:center;gap:.4rem;">
        <label style="font-size:.82rem;color:var(--text-muted);font-weight:700;">📅 Año:</label>
        <select name="year" onchange="this.form.submit()"
            style="padding:.45rem .8rem;border:1.5px solid var(--border-subtle);border-radius:10px;font-family:inherit;font-size:.85rem;outline:none;background:#fff;font-weight:600;color:var(--text-dark);">
            @for($y = now()->year; $y >= now()->year - 4; $y--)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </form>
    <a href="{{ route('reportes.pdf', ['year' => $year]) }}" class="btn-export-pdf" title="Descargar informe completo en formato PDF">
        📄 Exportar PDF
    </a>
    <a href="{{ route('reportes.excel', ['year' => $year]) }}" class="btn-export-excel" title="Descargar informe en hoja de cálculo Excel">
        📊 Exportar Excel
    </a>
</div>
@endsection

@push('styles')
<style>
    .btn-export-pdf {
        background: linear-gradient(135deg, #e11d48 0%, #f43f5e 100%);
        color: #fff; text-decoration: none; padding: .48rem 1rem;
        border-radius: 10px; font-family: inherit; font-size: .84rem; font-weight: 700;
        display: inline-flex; align-items: center; gap: .4rem;
        box-shadow: 0 4px 12px rgba(244,63,94,.25); transition: all .2s ease;
    }
    .btn-export-pdf:hover {
        transform: translateY(-2px); box-shadow: 0 6px 16px rgba(244,63,94,.38); color: #fff;
    }
    .btn-export-excel {
        background: linear-gradient(135deg, #0b4e37 0%, #159c6c 100%);
        color: #fff; text-decoration: none; padding: .48rem 1rem;
        border-radius: 10px; font-family: inherit; font-size: .84rem; font-weight: 700;
        display: inline-flex; align-items: center; gap: .4rem;
        box-shadow: 0 4px 12px rgba(21,156,108,.25); transition: all .2s ease;
    }
    .btn-export-excel:hover {
        transform: translateY(-2px); box-shadow: 0 6px 16px rgba(21,156,108,.38); color: #fff;
    }

    .stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.2rem; margin-bottom: 2rem; }
    .stat-card {
        border-radius: 16px; padding: 1.3rem 1.5rem; background: #fff;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 1rem;
        transition: transform .2s, box-shadow .2s;
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    .stat-card.green  { border-left: 4px solid #106f4e; }
    .stat-card.green .sc-icon { background: #ecfdf5; border: 1px solid #d1fae5; }
    .stat-card.blue   { border-left: 4px solid #2563eb; }
    .stat-card.blue .sc-icon { background: #eff6ff; border: 1px solid #dbeafe; }
    .stat-card.teal   { border-left: 4px solid #0d9488; }
    .stat-card.teal .sc-icon { background: #f0fdfa; border: 1px solid #ccfbf1; }
    .stat-card.orange { border-left: 4px solid #d97706; }
    .stat-card.orange .sc-icon { background: #fffbeb; border: 1px solid #fef3c7; }

    .stat-card .sc-icon {
        font-size: 1.6rem; width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .stat-card .sc-num  { font-size: 1.7rem; font-weight: 900; color: var(--text-dark); line-height: 1.1; }
    .stat-card .sc-label { font-size: .85rem; font-weight: 600; color: var(--text-muted); margin-top: .15rem; }

    .reports-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
    .rcard {
        background: #fff; border-radius: 18px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm); overflow: hidden;
    }
    .rcard-header {
        padding: 1.2rem 1.5rem; border-bottom: 1px solid var(--border-subtle);
        display: flex; align-items: center; gap: .6rem;
    }
    .rcard-header h3 { font-size: 1.05rem; font-weight: 800; color: var(--text-dark); }
    .rcard-body { padding: 1.4rem 1.5rem; }

    .empty-section { text-align: center; padding: 2.5rem; color: var(--text-muted); }
    .empty-section .empty-icon { font-size: 2.5rem; margin-bottom: .5rem; }
    .empty-section p { font-size: .88rem; }

    /* Ventas por mes */
    .month-row { display: flex; align-items: center; gap: .8rem; margin-bottom: .7rem; }
    .month-name { width: 34px; font-size: .82rem; font-weight: 700; color: var(--text-muted); }
    .month-bar-wrap { flex: 1; height: 22px; background: #f1f5f9; border-radius: 6px; overflow: hidden; }
    .month-bar { height: 100%; background: linear-gradient(90deg, #106f4e, #22c584); border-radius: 6px; transition: width .4s; }
    .month-value { font-size: .82rem; font-weight: 700; color: var(--text-dark); width: 85px; text-align: right; }
    .month-qty   { font-size: .76rem; color: var(--text-muted); width: 50px; text-align: right; }

    /* Top productos */
    .top-row { display: flex; align-items: center; gap: .8rem; padding: .6rem 0; border-bottom: 1px solid var(--border-subtle); }
    .top-rank {
        width: 24px; height: 24px; border-radius: 50%; background: #106f4e; color: #fff;
        font-size: .75rem; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .top-name { flex: 1; font-size: .88rem; color: var(--text-dark); font-weight: 600; }
    .top-qty  { font-size: .85rem; font-weight: 800; color: #106f4e; }
    .top-ing  { font-size: .8rem; color: var(--text-muted); }

    /* Stock bajo */
    .stock-row { display: flex; align-items: center; justify-content: space-between; padding: .6rem 0; border-bottom: 1px solid var(--border-subtle); }
    .stock-row:last-child { border-bottom: none; }
    .stock-name { font-size: .88rem; font-weight: 600; color: var(--text-dark); }
    .stock-qty  { font-size: .88rem; font-weight: 800; color: #f43f5e; }
    .badge-cat  { background: #f1f5f9; color: #334155; padding: .2rem .6rem; border-radius: 12px; font-size: .74rem; font-weight: 600; border: 1px solid #e2e8f0; }

    /* Compras por proveedor */
    .compra-row { display: flex; align-items: center; justify-content: space-between; padding: .6rem 0; border-bottom: 1px solid var(--border-subtle); }
    .compra-row:last-child { border-bottom: none; }
    .compra-name { font-size: .88rem; font-weight: 600; color: var(--text-dark); }
    .compra-val  { font-size: .88rem; font-weight: 800; color: #106f4e; }
</style>
@endpush

@section('content')

{{-- Stat cards --}}
<div class="stats-row">
    <div class="stat-card green">
        <div class="sc-icon">👥</div>
        <div><div class="sc-num">{{ $totalUsuarios }}</div><div class="sc-label">Usuarios</div></div>
    </div>
    <div class="stat-card blue">
        <div class="sc-icon">📦</div>
        <div><div class="sc-num">{{ $totalProductos }}</div><div class="sc-label">Productos</div></div>
    </div>
    <div class="stat-card teal">
        <div class="sc-icon">💰</div>
        <div><div class="sc-num">${{ number_format($totalVentas, 0) }}</div><div class="sc-label">Total Ventas</div></div>
    </div>
    <div class="stat-card orange">
        <div class="sc-icon">🚚</div>
        <div><div class="sc-num">${{ number_format($totalCompras, 0) }}</div><div class="sc-label">Compras</div></div>
    </div>
</div>

{{-- Reports Grid --}}
<div class="reports-grid">

    {{-- Ventas por mes --}}
    <div class="rcard">
        <div class="rcard-header">
            <span>📈</span>
            <h3>Ventas por Mes — {{ $year }}</h3>
        </div>
        <div class="rcard-body">
            @php $hasVentas = collect($meses)->sum('total') > 0; @endphp
            @if($hasVentas)
                @foreach($meses as $num => $mes)
                <div class="month-row">
                    <span class="month-name">{{ $mes['nombre'] }}</span>
                    <div class="month-bar-wrap">
                        <div class="month-bar" style="width:{{ $maxVenta > 0 ? ($mes['total']/$maxVenta*100) : 0 }}%"></div>
                    </div>
                    <span class="month-value">${{ number_format($mes['total'], 0) }}</span>
                    <span class="month-qty">{{ $mes['cantidad'] }} v.</span>
                </div>
                @endforeach
            @else
                <div class="empty-section">
                    <div class="empty-icon">📊</div>
                    <p>Sin datos de ventas para este año.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Top 10 productos --}}
    <div class="rcard">
        <div class="rcard-header">
            <span>🏆</span>
            <h3>Top 10 Productos Más Vendidos</h3>
        </div>
        <div class="rcard-body">
            @if($topProductos->count() > 0)
                @foreach($topProductos as $i => $p)
                <div class="top-row">
                    <div class="top-rank">{{ $i + 1 }}</div>
                    <div class="top-name">{{ $p->nombre }}</div>
                    <div>
                        <div class="top-qty">{{ number_format($p->total_vendido) }} uds.</div>
                        <div class="top-ing">${{ number_format($p->ingresos, 0) }}</div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-section">
                    <div class="empty-icon">🏆</div>
                    <p>Sin datos de ventas aún.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Productos con stock bajo --}}
    <div class="rcard">
        <div class="rcard-header">
            <span>⚠️</span>
            <h3>Productos con Stock Bajo</h3>
        </div>
        <div class="rcard-body">
            @if($productosStockBajo->count() > 0)
                @foreach($productosStockBajo as $p)
                <div class="stock-row">
                    <div>
                        <div class="stock-name">{{ $p->nombre }}</div>
                        @if($p->categoria)<span class="badge-cat">{{ $p->categoria->nombre }}</span>@endif
                    </div>
                    <div class="stock-qty">{{ $p->stock_actual }} / {{ $p->stock_minimo }}</div>
                </div>
                @endforeach
            @else
                <div class="empty-section">
                    <div class="empty-icon" style="color:#16a34a;">✅</div>
                    <p>¡Todo el stock está en orden!</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Compras por proveedor --}}
    <div class="rcard">
        <div class="rcard-header">
            <span>🚚</span>
            <h3>Compras por Proveedor</h3>
        </div>
        <div class="rcard-body">
            @if($comprasPorProveedor->count() > 0)
                @foreach($comprasPorProveedor as $c)
                <div class="compra-row">
                    <div>
                        <div class="compra-name">{{ $c->nombre }}</div>
                        <div class="compra-qty">{{ $c->total_compras }} compra(s)</div>
                    </div>
                    <div class="compra-total">${{ number_format($c->total_gastado, 0) }}</div>
                </div>
                @endforeach
            @else
                <div class="empty-section">
                    <div class="empty-icon">🚚</div>
                    <p>Sin compras registradas.</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
