@extends('layouts.sidebar')

@section('title', 'Reportes')
@section('page-title', 'Reportes')
@section('page-subtitle', 'Estadísticas y análisis del negocio')

@section('page-action')
<div style="display:flex;align-items:center;gap:.8rem;">
    <form method="GET" action="{{ route('reportes.index') }}" style="display:flex;align-items:center;gap:.5rem;">
        <label style="font-size:.82rem;color:#6b7280;font-weight:600;">📅 Año:</label>
        <select name="year" onchange="this.form.submit()"
            style="padding:.4rem .7rem;border:1.5px solid #e5e7eb;border-radius:8px;font-family:inherit;font-size:.85rem;outline:none;">
            @for($y = now()->year; $y >= now()->year - 4; $y--)
            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </form>
</div>
@endsection

@push('styles')
<style>
    .stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.2rem; margin-bottom: 1.8rem; }
    .stat-card {
        border-radius: 14px; padding: 1.2rem 1.4rem;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); display: flex; align-items: center; gap: 1rem;
    }
    .stat-card.green  { background: #d1fae5; }
    .stat-card.blue   { background: #dbeafe; }
    .stat-card.teal   { background: #ccfbf1; }
    .stat-card.orange { background: #ffedd5; }
    .stat-card .sc-icon { font-size: 1.8rem; }
    .stat-card .sc-num  { font-size: 1.6rem; font-weight: 900; color: #111827; }
    .stat-card .sc-label { font-size: .85rem; font-weight: 600; color: #374151; }

    .reports-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.4rem; }
    .rcard { background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.06); overflow: hidden; }
    .rcard-header { padding: 1rem 1.4rem; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; gap: .5rem; }
    .rcard-header h3 { font-size: .95rem; font-weight: 700; color: #111827; }
    .rcard-body { padding: 1.2rem 1.4rem; }

    .empty-section { text-align: center; padding: 2rem; color: #9ca3af; }
    .empty-section .empty-icon { font-size: 2.5rem; margin-bottom: .5rem; }
    .empty-section p { font-size: .85rem; }

    /* Ventas por mes */
    .month-row { display: flex; align-items: center; gap: .8rem; margin-bottom: .6rem; }
    .month-name { width: 30px; font-size: .78rem; font-weight: 600; color: #6b7280; }
    .month-bar-wrap { flex: 1; height: 20px; background: #f3f4f6; border-radius: 5px; overflow: hidden; }
    .month-bar { height: 100%; background: linear-gradient(90deg, #16a34a, #4ade80); border-radius: 5px; transition: width .4s; }
    .month-value { font-size: .78rem; font-weight: 700; color: #374151; width: 80px; text-align: right; }
    .month-qty   { font-size: .75rem; color: #9ca3af; width: 50px; text-align: right; }

    /* Top productos */
    .top-row { display: flex; align-items: center; gap: .7rem; padding: .45rem 0; border-bottom: 1px solid #f9fafb; }
    .top-rank { width: 22px; height: 22px; border-radius: 50%; background: #16a34a; color: #fff; font-size: .72rem; font-weight: 800; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .top-name { flex: 1; font-size: .85rem; color: #111827; font-weight: 500; }
    .top-qty  { font-size: .82rem; font-weight: 700; color: #16a34a; }
    .top-ing  { font-size: .78rem; color: #9ca3af; }

    /* Stock bajo */
    .stock-row { display: flex; align-items: center; justify-content: space-between; padding: .5rem 0; border-bottom: 1px solid #f9fafb; }
    .stock-row:last-child { border-bottom: none; }
    .stock-name { font-size: .85rem; font-weight: 500; color: #111827; }
    .stock-qty  { font-size: .85rem; font-weight: 700; color: #ef4444; }
    .badge-cat  { background: #ede9fe; color: #6d28d9; padding: .18rem .55rem; border-radius: 10px; font-size: .72rem; font-weight: 600; }

    /* Compras por proveedor */
    .compra-row { display: flex; align-items: center; justify-content: space-between; padding: .5rem 0; border-bottom: 1px solid #f9fafb; }
    .compra-row:last-child { border-bottom: none; }
    .compra-name  { font-size: .85rem; font-weight: 600; color: #111827; }
    .compra-total { font-size: .85rem; font-weight: 700; color: #16a34a; }
    .compra-qty   { font-size: .75rem; color: #9ca3af; }
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
