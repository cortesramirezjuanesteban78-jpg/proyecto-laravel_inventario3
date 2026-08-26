@extends('layouts.sidebar')

@section('title', 'Inventario')
@section('page-title', 'Inventario')
@section('page-subtitle', 'Stock en tiempo real de todos los productos activos')

@section('page-action')
<div style="display:flex;align-items:center;gap:.8rem;">
    <span id="live-badge" style="display:flex;align-items:center;gap:.4rem;background:#d1fae5;border:1px solid #6ee7b7;color:#065f46;padding:.3rem .8rem;border-radius:20px;font-size:.78rem;font-weight:700;">
        <span id="live-dot" style="width:8px;height:8px;border-radius:50%;background:#16a34a;animation:pulse-dot 2s infinite;"></span>
        En vivo · <span id="live-time">--:--:--</span>
    </span>
    <span style="font-size:.85rem;color:#6b7280;">Usa el botón "Mover" en cada fila para registrar movimientos</span>
</div>
<style>@keyframes pulse-dot{0%,100%{opacity:1}50%{opacity:.3}}</style>
@endsection

@push('styles')
<style>
    /* Stats */
    .stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.2rem; margin-bottom: 1.8rem; }
    .stat-card {
        border-radius: 14px; padding: 1.2rem 1.4rem;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); display: flex; align-items: center; gap: 1rem;
    }
    .stat-card.green  { background: #d1fae5; }
    .stat-card.blue   { background: #dbeafe; }
    .stat-card.orange { background: #ffedd5; }
    .stat-card.teal   { background: #ccfbf1; }
    .stat-card .sc-icon { font-size: 1.8rem; }
    .stat-card .sc-num  { font-size: 1.8rem; font-weight: 900; color: #111827; }
    .stat-card .sc-label { font-size: .85rem; font-weight: 600; color: #374151; }

    /* Filters */
    .filters-row { display: flex; gap: .8rem; margin-bottom: 1.4rem; }
    .filters-row input {
        background: #f3f4f6; border: none; border-radius: 8px;
        padding: .5rem .9rem; font-family: inherit; font-size: .85rem;
        outline: none; width: 260px;
    }
    .filters-row input:focus { box-shadow: 0 0 0 2px #bbf7d0; }

    /* Table */
    .card { background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.06); overflow: hidden; }
    .card-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1rem 1.4rem; border-bottom: 1px solid #f3f4f6;
    }
    .card-header h2 { font-size: 1rem; font-weight: 700; color: #111827; }
    table { width: 100%; border-collapse: collapse; }
    thead th {
        background: #f0fdf4; color: #15803d; font-weight: 600;
        font-size: .78rem; text-transform: uppercase; letter-spacing: .05em;
        padding: .75rem 1rem; text-align: left;
    }
    tbody tr { border-bottom: 1px solid #f3f4f6; }
    tbody tr:hover { background: #fafafa; }
    tbody td { padding: .7rem 1rem; font-size: .88rem; color: #374151; vertical-align: middle; }

    .badge {
        padding: .22rem .65rem; border-radius: 20px; font-size: .75rem; font-weight: 700;
    }
    .badge-cat   { background: #ede9fe; color: #6d28d9; }
    .badge-ok    { background: #dcfce7; color: #15803d; }
    .badge-bajo  { background: #ffedd5; color: #c2410c; }
    .badge-agot  { background: #fee2e2; color: #b91c1c; }

    .stock-bar { height: 6px; border-radius: 3px; background: #e5e7eb; overflow: hidden; min-width: 60px; }
    .stock-fill { height: 100%; border-radius: 3px; }
    .stock-fill.ok  { background: #16a34a; }
    .stock-fill.low { background: #f97316; }
    .stock-fill.out { background: #ef4444; }

    .btn-orange {
        padding: .32rem .8rem; border-radius: 7px; border: none; background: #f97316;
        color: #fff; font-family: inherit; font-size: .8rem; font-weight: 700; cursor: pointer;
    }
    .btn-orange:hover { background: #ea580c; }

    .pag-wrap { padding: .8rem 1.4rem; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end; }

    /* Modal */
    .modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45);
        z-index: 200; align-items: center; justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
        background: #fff; border-radius: 16px; padding: 2rem;
        width: 100%; max-width: 420px; position: relative;
        box-shadow: 0 20px 60px rgba(0,0,0,.2);
    }
    .modal-title { font-size: 1.1rem; font-weight: 800; color: #111827; margin-bottom: 1.2rem; }
    .form-group { margin-bottom: .9rem; }
    .form-group label { display: block; font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: .3rem; }
    .form-group input, .form-group select, .form-group textarea {
        width: 100%; padding: .55rem .8rem; border: 1.5px solid #e5e7eb; border-radius: 8px;
        font-family: inherit; font-size: .88rem; outline: none;
    }
    .form-group input:focus, .form-group select:focus { border-color: #f97316; box-shadow: 0 0 0 2px #fed7aa; }
    .modal-footer { display: flex; gap: .7rem; justify-content: flex-end; margin-top: .5rem; }
    .btn-secondary {
        padding: .5rem 1.1rem; border-radius: 8px; border: 1.5px solid #e5e7eb;
        background: #fff; font-family: inherit; font-size: .87rem; font-weight: 600; cursor: pointer;
    }
    .modal-close {
        position: absolute; top: 1rem; right: 1rem; background: #f3f4f6; border: none;
        border-radius: 7px; width: 28px; height: 28px; cursor: pointer; font-size: .95rem;
    }
    .modal-close:hover { background: #fee2e2; color: #b91c1c; }
    .modal-producto-name {
        background: #fff7ed; border: 1px solid #fed7aa; border-radius: 8px;
        padding: .5rem .8rem; font-weight: 700; font-size: .88rem; color: #c2410c;
        margin-bottom: 1rem;
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

function renderRows(productos) {
    if (!productos.length) {
        tbody.innerHTML = `<tr><td colspan="9" style="text-align:center;padding:2rem;color:#9ca3af;">No hay productos activos.</td></tr>`;
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
