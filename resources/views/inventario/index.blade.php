@extends('layouts.sidebar')

@section('title', 'Inventario')
@section('page-title', 'Inventario')
@section('page-subtitle', 'Stock en tiempo real de todos los productos activos')

@section('page-action')
<div style="display:flex;align-items:center;gap:.8rem;">
    <span id="live-badge" style="display:flex;align-items:center;gap:.45rem;background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;padding:.35rem .9rem;border-radius:20px;font-size:.8rem;font-weight:800;box-shadow:var(--shadow-sm);">
        <span id="live-dot" style="width:8px;height:8px;border-radius:50%;background:#159c6c;animation:pulse-dot 2s infinite;"></span>
        En vivo · <span id="live-time">--:--:--</span>
    </span>
    <span style="font-size:.85rem;color:var(--text-muted);">Usa el botón "Mover" en cada fila para registrar movimientos</span>
</div>
<style>@keyframes pulse-dot{0%,100%{opacity:1}50%{opacity:.35}}</style>
@endsection

@push('styles')
<style>
    /* Stats */
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
    .stat-card.orange { border-left: 4px solid #d97706; }
    .stat-card.orange .sc-icon { background: #fffbeb; border: 1px solid #fef3c7; }
    .stat-card.teal   { border-left: 4px solid #0d9488; }
    .stat-card.teal .sc-icon { background: #f0fdfa; border: 1px solid #ccfbf1; }

    .stat-card .sc-icon {
        font-size: 1.6rem; width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .stat-card .sc-num  { font-size: 1.8rem; font-weight: 900; color: var(--text-dark); line-height: 1.1; }
    .stat-card .sc-label { font-size: .85rem; font-weight: 600; color: var(--text-muted); margin-top: .15rem; }

    /* Filters */
    .filters-row { display: flex; gap: .8rem; margin-bottom: 1.6rem; }
    .filters-row input {
        background: #fff; border: 1.5px solid var(--border-subtle); border-radius: 10px;
        padding: .55rem 1rem; font-family: inherit; font-size: .88rem;
        outline: none; width: 280px; transition: all .2s;
    }
    .filters-row input:focus {
        border-color: #159c6c; box-shadow: 0 0 0 3px rgba(21,156,108,.15);
    }

    /* Table */
    .card {
        background: #fff; border-radius: 18px;
        border: 1px solid var(--border-light);
        box-shadow: var(--shadow-sm); overflow: hidden;
    }
    .card-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1.2rem 1.6rem; border-bottom: 1px solid var(--border-subtle);
    }
    .card-header h2 { font-size: 1.05rem; font-weight: 800; color: var(--text-dark); }
    table { width: 100%; border-collapse: collapse; }
    thead th {
        background: #f8fafc; color: var(--text-muted); font-weight: 700;
        font-size: .78rem; text-transform: uppercase; letter-spacing: .06em;
        padding: .85rem 1.2rem; text-align: left; border-bottom: 1px solid var(--border-subtle);
    }
    tbody tr { border-bottom: 1px solid var(--border-subtle); transition: background .15s; }
    tbody tr:hover { background: #f8fafc; }
    tbody td { padding: .85rem 1.2rem; font-size: .9rem; color: var(--text-dark); vertical-align: middle; }

    .badge {
        padding: .25rem .75rem; border-radius: 20px; font-size: .76rem; font-weight: 700;
    }
    .badge-cat   { background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; }
    .badge-ok    { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-bajo  { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .badge-agot  { background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3; }

    .stock-bar { height: 6px; border-radius: 3px; background: #e2e8f0; overflow: hidden; min-width: 60px; }
    .stock-fill { height: 100%; border-radius: 3px; }
    .stock-fill.ok  { background: #159c6c; }
    .stock-fill.low { background: #f59e0b; }
    .stock-fill.out { background: #f43f5e; }

    .btn-orange {
        padding: .4rem .9rem; border-radius: 8px; border: none;
        background: linear-gradient(135deg, #0b4e37 0%, #159c6c 100%);
        color: #fff; font-family: inherit; font-size: .82rem; font-weight: 700; cursor: pointer;
        box-shadow: 0 2px 8px rgba(16,111,78,.2); transition: all .2s;
    }
    .btn-orange:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16,111,78,.35); }

    .pag-wrap { padding: 1rem 1.6rem; border-top: 1px solid var(--border-subtle); display: flex; justify-content: flex-end; }

    /* Modal */
    .modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(15,23,42,.5);
        backdrop-filter: blur(4px);
        z-index: 200; align-items: center; justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
        background: #fff; border-radius: 20px; padding: 2.2rem;
        width: 100%; max-width: 440px; position: relative;
        box-shadow: var(--shadow-lg);
    }
    .modal-title { font-size: 1.25rem; font-weight: 800; color: var(--text-dark); margin-bottom: 1.4rem; }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-size: .82rem; font-weight: 700; color: #334155; margin-bottom: .35rem; }
    .form-group input, .form-group select, .form-group textarea {
        width: 100%; padding: .65rem .85rem; border: 1.5px solid var(--border-subtle); border-radius: 10px;
        font-family: inherit; font-size: .9rem; outline: none; transition: all .2s;
    }
    .form-group input:focus, .form-group select:focus {
        border-color: #159c6c; box-shadow: 0 0 0 3px rgba(21,156,108,.15);
    }
    .modal-footer { display: flex; gap: .8rem; justify-content: flex-end; margin-top: .8rem; }
    .btn-secondary {
        padding: .55rem 1.15rem; border-radius: 10px; border: 1.5px solid var(--border-subtle);
        background: #fff; font-family: inherit; font-size: .88rem; font-weight: 700; cursor: pointer; color: var(--text-dark);
        transition: all .2s;
    }
    .btn-secondary:hover { background: #f8fafc; border-color: #cbd5e1; }
    .modal-close {
        position: absolute; top: 1.2rem; right: 1.2rem; background: #f1f5f9; border: none;
        border-radius: 8px; width: 30px; height: 30px; cursor: pointer; font-size: 1rem;
        color: var(--text-muted); transition: all .2s;
    }
    .modal-close:hover { background: #fff1f2; color: #f43f5e; }
    .modal-producto-name {
        background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 10px;
        padding: .6rem .9rem; font-weight: 700; font-size: .9rem; color: #065f46;
        margin-bottom: 1.2rem;
    }

    /* Alert Box */
    .inventario-alert-box {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        border: 1.5px solid #fcd34d;
        border-radius: 16px;
        padding: 1rem 1.4rem;
        margin-bottom: 1.6rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.2rem;
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.08);
    }
    .iab-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .iab-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #fef08a;
        border: 1px solid #fde047;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .iab-text strong {
        display: block;
        color: #92400e;
        font-size: 0.95rem;
        font-weight: 800;
        margin-bottom: 0.15rem;
    }
    .iab-text p {
        color: #b45309;
        font-size: 0.82rem;
        margin: 0;
    }
    .btn-filter-low {
        background: #d97706;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 0.5rem 1.1rem;
        font-family: inherit;
        font-size: 0.84rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 6px rgba(217, 119, 6, 0.25);
        white-space: nowrap;
    }
    .btn-filter-low:hover {
        background: #b45309;
        transform: translateY(-1px);
    }
    .btn-filter-low.active {
        background: #92400e;
    }
</style>
@endpush

@section('content')

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-card green">
        <div class="sc-icon">✅</div>
        <div><div class="sc-num" id="stat-activos">{{ $productosActivos }}</div><div class="sc-label">Productos Activos</div></div>
    </div>
    <div class="stat-card blue">
        <div class="sc-icon">📊</div>
        <div><div class="sc-num" id="stat-unidades">{{ number_format($totalUnidades) }}</div><div class="sc-label">Total Unidades</div></div>
    </div>
    <div class="stat-card orange">
        <div class="sc-icon">⚠️</div>
        <div><div class="sc-num" id="stat-bajo">{{ $stockBajo }}</div><div class="sc-label">Stock Bajo</div></div>
    </div>
    <div class="stat-card teal">
        <div class="sc-icon">💰</div>
        <div><div class="sc-num" id="stat-valor">${{ number_format($valorTotal, 0) }}</div><div class="sc-label">Valor Total</div></div>
    </div>
</div>

@if($stockBajo > 0)
<div class="inventario-alert-box">
    <div class="iab-left">
        <div class="iab-icon">⚠️</div>
        <div class="iab-text">
            <strong>¡Atención Operativa! Hay {{ $stockBajo }} {{ $stockBajo == 1 ? 'producto' : 'productos' }} con Stock Bajo o Agotado.</strong>
            <p>Las existencias están igual o por debajo del stock mínimo estipulado. Se recomienda gestionar abastecimiento con proveedores.</p>
        </div>
    </div>
    <button type="button" class="btn-filter-low" id="btnFilterLow" onclick="toggleLowStockFilter()">
        ⚠️ Ver solo stock bajo ({{ $stockBajo }})
    </button>
</div>
@endif

{{-- Search --}}
<form method="GET" action="{{ route('inventario.index') }}" class="filters-row">
    <input type="text" name="search" placeholder="🔍 Buscar producto o código..." value="{{ request('search') }}">
    <button type="submit" style="padding:.5rem 1rem;border-radius:8px;background:#16a34a;color:#fff;border:none;cursor:pointer;font-weight:600;">
        Buscar
    </button>
    @if(request('search'))
    <a href="{{ route('inventario.index') }}" style="color:#6b7280;font-size:.85rem;text-decoration:none;">✕ Limpiar</a>
    @endif
</form>

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <h2>📋 Control de Stock</h2>
        <span style="font-size:.82rem;color:#9ca3af;" id="total-count">{{ $productos->total() }} productos</span>
    </div>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Código</th>
                <th>Stock Actual</th>
                <th>Mínimo</th>
                <th>Nivel</th>
                <th>Precio Unit.</th>
                <th>Valor Stock</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody id="inventory-tbody">
        @forelse($productos as $p)
        @php
            if ($p->stock_actual == 0) { $level = 'agot'; $levelLabel = 'AGOTADO'; $stockClass = 'out'; }
            elseif ($p->stock_actual <= $p->stock_minimo) { $level = 'bajo'; $levelLabel = 'BAJO'; $stockClass = 'low'; }
            else { $level = 'ok'; $levelLabel = 'OK'; $stockClass = 'ok'; }
            $pct = $p->stock_minimo > 0 ? min(100, ($p->stock_actual / ($p->stock_minimo * 2)) * 100) : min(100, $p->stock_actual * 5);
        @endphp
        <tr>
            <td><strong>{{ $p->nombre }}</strong></td>
            <td>
                @if($p->categoria)
                <span class="badge badge-cat">{{ $p->categoria->nombre }}</span>
                @else —
                @endif
            </td>
            <td><code style="font-size:.8rem;background:#f3f4f6;padding:.1rem .4rem;border-radius:4px;">{{ $p->codigo }}</code></td>
            <td>
                <div style="font-weight:700;margin-bottom:.25rem;">{{ $p->stock_actual }}</div>
                <div class="stock-bar">
                    <div class="stock-fill {{ $stockClass }}" style="width:{{ $pct }}%"></div>
                </div>
            </td>
            <td>{{ $p->stock_minimo }}</td>
            <td>
                <span class="badge
                    @if($level === 'ok') badge-ok
                    @elseif($level === 'bajo') badge-bajo
                    @else badge-agot @endif">
                    {{ $levelLabel }}
                </span>
            </td>
            <td>${{ number_format($p->precio, 2) }}</td>
            <td><strong>${{ number_format($p->stock_actual * $p->precio, 2) }}</strong></td>
            <td>
                <button class="btn-orange"
                    onclick="openMoverModal({{ $p->id_producto }}, '{{ addslashes($p->nombre) }}')">
                    📦 Mover
                </button>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" style="text-align:center;padding:2rem;color:#9ca3af;">No hay productos activos.</td>
        </tr>
        @endforelse
        </tbody>
    </table>
    @if($productos->hasPages())
    <div class="pag-wrap">{{ $productos->links() }}</div>
    @endif
</div>

{{-- Mover Modal --}}
<div class="modal-overlay" id="modal-mover">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('modal-mover')">✕</button>
        <div class="modal-title">📦 Registrar Movimiento</div>
        <div class="modal-producto-name" id="mover-nombre">—</div>
        <form method="POST" id="mover-form" action="">
            @csrf
            <div class="form-group">
                <label>Tipo de Movimiento *</label>
                <select name="tipo_movimiento" required>
                    <option value="entrada">📥 Entrada (agregar stock)</option>
                    <option value="salida">📤 Salida (reducir stock)</option>
                    <option value="ajuste">🔧 Ajuste (fijar cantidad exacta)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Cantidad *</label>
                <input type="number" name="cantidad" min="1" required placeholder="Ej: 10">
            </div>
            <div class="form-group">
                <label>Observación</label>
                <textarea name="observacion" rows="2" placeholder="Motivo del movimiento..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-mover')">Cancelar</button>
                <button type="submit" class="btn-orange">Registrar</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }
function openMoverModal(id, nombre) {
    document.getElementById('mover-form').action = '/inventario/' + id + '/mover';
    document.getElementById('mover-nombre').textContent = nombre;
    openModal('modal-mover');
}
document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});

// ── Auto-refresh del inventario cada 8 segundos ──────────────────────────
const POLL_INTERVAL = 8000;
const searchInput   = document.querySelector('input[name="search"]');
const tbody         = document.getElementById('inventory-tbody');

function fmtMoney(n) {
    return '$' + Number(n).toLocaleString('es', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
}
function fmtNum(n) {
    return Number(n).toLocaleString('es');
}

let showingOnlyLow = false;
function toggleLowStockFilter() {
    showingOnlyLow = !showingOnlyLow;
    const btn = document.getElementById('btnFilterLow');
    if (btn) {
        btn.classList.toggle('active', showingOnlyLow);
        btn.textContent = showingOnlyLow ? '📋 Mostrar todos los productos' : '⚠️ Ver solo stock bajo ({{ $stockBajo }})';
    }
    const rows = document.querySelectorAll('#inventory-tbody tr');
    rows.forEach(r => {
        const badge = r.querySelector('.badge-bajo, .badge-agot');
        if (showingOnlyLow) {
            r.style.display = badge ? '' : 'none';
        } else {
            r.style.display = '';
        }
    });
}

function renderRows(productos) {
    if (showingOnlyLow) {
        productos = productos.filter(p => p.level === 'bajo' || p.level === 'agot');
    }

    if (!productos.length) {
        const msg = showingOnlyLow ? 'No hay productos con stock bajo o agotado actualmente.' : 'No hay productos activos.';
        tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;padding:2rem;color:#9ca3af;">${msg}</td></tr>`;
        return;
    }

    const levelClass = { ok: 'badge-ok', bajo: 'badge-bajo', agot: 'badge-agot' };
    const stockClass = { ok: 'ok', bajo: 'low', agot: 'out' };

    tbody.innerHTML = productos.map(p => `
        <tr data-id="${p.id}">
            <td><strong>${escHtml(p.nombre)}</strong></td>
            <td>${p.categoria
                ? `<span class="badge badge-cat">${escHtml(p.categoria)}</span>`
                : '—'}</td>
            <td><code style="font-size:.8rem;background:#f3f4f6;padding:.1rem .4rem;border-radius:4px;">${escHtml(p.codigo)}</code></td>
            <td>
                <div style="font-weight:700;margin-bottom:.25rem;">${p.stock_actual}</div>
                <div class="stock-bar">
                    <div class="stock-fill ${stockClass[p.level]}" style="width:${p.pct}%"></div>
                </div>
            </td>
            <td>${p.stock_minimo}</td>
            <td><span class="badge ${levelClass[p.level]}">${escHtml(p.level_label)}</span></td>
            <td>${fmtMoney(p.precio)}</td>
            <td><strong>${fmtMoney(p.valor_stock)}</strong></td>
            <td>
                <button class="btn-orange"
                    onclick="openMoverModal(${p.id}, '${escJs(p.nombre)}')">
                    📦 Mover
                </button>
            </td>
        </tr>
    `).join('');
}

function escHtml(str) {
    return String(str)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function escJs(str) {
    return String(str).replace(/'/g,"\\'").replace(/\\/g,'\\\\');
}

function flashUpdate() {
    // pulsa el badge verde brevemente
    const dot = document.getElementById('live-dot');
    dot.style.background = '#4ade80';
    dot.style.transform  = 'scale(1.6)';
    setTimeout(() => {
        dot.style.background = '#16a34a';
        dot.style.transform  = 'scale(1)';
    }, 500);
}

async function pollInventario() {
    const search = searchInput ? searchInput.value : '';
    const url    = '{{ route("inventario.stock-live") }}' + (search ? '?search=' + encodeURIComponent(search) : '');

    try {
        const res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        if (!res.ok) return;
        const data = await res.json();

        // Actualizar stats
        document.getElementById('stat-activos').textContent  = fmtNum(data.stats.productosActivos);
        document.getElementById('stat-unidades').textContent = fmtNum(data.stats.totalUnidades);
        document.getElementById('stat-bajo').textContent     = fmtNum(data.stats.stockBajo);
        document.getElementById('stat-valor').textContent    = fmtMoney(data.stats.valorTotal);

        // Actualizar contador
        const counter = document.getElementById('total-count');
        if (counter) counter.textContent = data.productos.length + ' productos';

        // Actualizar filas (solo si el modal de mover no está abierto)
        const moverOpen = document.getElementById('modal-mover').classList.contains('open');
        if (!moverOpen) renderRows(data.productos);

        // Actualizar timestamp
        document.getElementById('live-time').textContent = data.timestamp;

        flashUpdate();

    } catch (e) {
        // silencioso — no interrumpir al usuario si hay error de red
    }
}

// Primera carga desde servidor (ya renderizada por Blade), luego empieza el polling
setTimeout(pollInventario, POLL_INTERVAL);
setInterval(pollInventario, POLL_INTERVAL);

// También actualizar inmediatamente después de guardar un movimiento
document.getElementById('mover-form').addEventListener('submit', function() {
    // Dar tiempo al servidor para procesar y luego refrescar
    setTimeout(pollInventario, 800);
});
</script>
@endpush
