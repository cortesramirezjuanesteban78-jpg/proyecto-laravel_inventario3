@extends('layouts.sidebar')

@section('title', 'Ventas')
@section('page-title', 'Ventas')
@section('page-subtitle', 'Registra ventas y consulta el historial')

@section('page-action')
<button onclick="openModal('modal-create')" class="btn-primary">+ Nueva Venta</button>
@endsection

@push('styles')
<style>
    .btn-primary {
        background: #16a34a; color: #fff; border: none; padding: .55rem 1.2rem;
        border-radius: 8px; font-family: inherit; font-size: .88rem; font-weight: 600; cursor: pointer;
    }
    .btn-primary:hover { background: #15803d; }
    .btn-secondary {
        padding: .5rem 1.1rem; border-radius: 8px; border: 1.5px solid #e5e7eb;
        background: #fff; font-family: inherit; font-size: .87rem; font-weight: 600; cursor: pointer;
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
    .stat-card.purple { border-left: 4px solid #7c3aed; }
    .stat-card.purple .sc-icon { background: #f5f3ff; border: 1px solid #ede9fe; }

    .stat-card .sc-icon {
        font-size: 1.6rem; width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .stat-card .sc-num  { font-size: 1.7rem; font-weight: 900; color: var(--text-dark); line-height: 1.1; }
    .stat-card .sc-label { font-size: .85rem; font-weight: 600; color: var(--text-muted); margin-top: .15rem; }

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
    .search-wrap input {
        background: #fff; border: 1.5px solid var(--border-subtle); border-radius: 10px;
        padding: .5rem 1rem; font-family: inherit; font-size: .88rem; width: 240px; outline: none;
        transition: all .2s;
    }
    .search-wrap input:focus {
        border-color: #159c6c; box-shadow: 0 0 0 3px rgba(21,156,108,.15);
    }

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
    .badge-completada { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
    .badge-pendiente  { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .badge-cancelada  { background: #fff1f2; color: #9f1239; border: 1px solid #fecdd3; }

    .empty-state { text-align: center; padding: 3.5rem; color: var(--text-muted); }
    .empty-state .empty-icon { font-size: 3.2rem; margin-bottom: .8rem; }

    .actions { display: flex; gap: .45rem; }
    .btn-icon {
        width: 32px; height: 32px; border-radius: 8px; border: none; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .9rem; background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3;
        transition: all .15s;
    }
    .btn-icon:hover { transform: scale(1.1); }

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
        width: 100%; max-width: 580px; position: relative;
        box-shadow: var(--shadow-lg); max-height: 92vh; overflow-y: auto;
    }
    .modal-title { font-size: 1.25rem; font-weight: 800; color: var(--text-dark); margin-bottom: 1.4rem; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: .85rem; }
    .form-group { margin-bottom: 1rem; }
    .form-group label { display: block; font-size: .82rem; font-weight: 700; color: #334155; margin-bottom: .35rem; }
    .form-group input, .form-group select {
        width: 100%; padding: .65rem .85rem; border: 1.5px solid var(--border-subtle); border-radius: 10px;
        font-family: inherit; font-size: .9rem; outline: none; transition: all .2s;
    }
    .form-group input:focus, .form-group select:focus {
        border-color: #159c6c; box-shadow: 0 0 0 3px rgba(21,156,108,.15);
    }
    .modal-footer { display: flex; gap: .8rem; justify-content: flex-end; margin-top: .8rem; }
    .modal-close {
        position: absolute; top: 1.2rem; right: 1.2rem; background: #f1f5f9; border: none;
        border-radius: 8px; width: 30px; height: 30px; cursor: pointer; font-size: 1rem;
        color: var(--text-muted); transition: all .2s;
    }
    .modal-close:hover { background: #fff1f2; color: #f43f5e; }

    /* Product rows in venta */
    .product-rows { border: 1.5px solid var(--border-subtle); border-radius: 12px; padding: 1rem; margin-bottom: .6rem; background: #fafbfc; }
    .product-row { display: grid; grid-template-columns: 1fr 90px 32px; gap: .6rem; align-items: center; margin-bottom: .6rem; }
    .product-row:last-child { margin-bottom: 0; }
    .product-row select, .product-row input {
        padding: .5rem .7rem; border: 1.5px solid var(--border-subtle); border-radius: 8px;
        font-family: inherit; font-size: .86rem; outline: none; background: #fff;
    }
    .btn-remove-row {
        background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; border-radius: 8px;
        width: 32px; height: 32px; cursor: pointer; font-size: .95rem;
        display: flex; align-items: center; justify-content: center; transition: all .15s;
    }
    .btn-remove-row:hover { transform: scale(1.08); background: #fee2e2; }
    .btn-add-row {
        background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; border-radius: 8px;
        padding: .45rem .9rem; font-family: inherit; font-size: .84rem; font-weight: 700;
        cursor: pointer; margin-top: .4rem; transition: all .2s;
    }
    .btn-add-row:hover { background: #d1fae5; }
    .total-display {
        background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;
        padding: .5rem .9rem; font-weight: 700; font-size: .9rem; color: #065f46;
        margin-bottom: .8rem;
    }
</style>
@endpush

@section('content')

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-card green">
        <div class="sc-icon">🛒</div>
        <div><div class="sc-num">{{ $totalVentas }}</div><div class="sc-label">Total Ventas</div></div>
    </div>
    <div class="stat-card blue">
        <div class="sc-icon">📅</div>
        <div><div class="sc-num">{{ $ventasHoy }}</div><div class="sc-label">Ventas Hoy</div></div>
    </div>
    <div class="stat-card teal">
        <div class="sc-icon">💵</div>
        <div><div class="sc-num">${{ number_format($ingresosHoy, 2) }}</div><div class="sc-label">Ingresos Hoy</div></div>
    </div>
    <div class="stat-card purple">
        <div class="sc-icon">💰</div>
        <div><div class="sc-num">${{ number_format($totalIngresos, 0) }}</div><div class="sc-label">Total Ingresos</div></div>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-header">
        <h2>📋 Historial de Ventas</h2>
        <div class="search-wrap">
            <input type="text" id="search-input" placeholder="🔍 Buscar..." onkeyup="filterTable()">
        </div>
    </div>

    @if($ventas->count() > 0)
    <table id="ventas-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Cajero</th>
                <th>Cliente</th>
                <th>Método Pago</th>
                <th>Estado</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach($ventas as $v)
        <tr>
            <td>#{{ $v->id_venta }}</td>
            <td>{{ \Carbon\Carbon::parse($v->fecha_venta)->format('d/m/Y H:i') }}</td>
            <td>{{ $v->usuario ? $v->usuario->nombres . ' ' . $v->usuario->apellidos : '—' }}</td>
            <td>{{ $v->cliente ? $v->cliente->nombre : 'Público General' }}</td>
            <td>{{ ucfirst($v->metodo_pago) }}</td>
            <td>
                <span class="badge
                    @if($v->estado === 'completada') badge-completada
                    @elseif($v->estado === 'pendiente') badge-pendiente
                    @else badge-cancelada @endif">
                    {{ ucfirst($v->estado) }}
                </span>
            </td>
            <td><strong>${{ number_format($v->total, 2) }}</strong></td>
            <td>
                <div class="actions">
                    <form method="POST" action="{{ route('ventas.destroy', $v->id_venta) }}"
                          onsubmit="return confirm('¿Eliminar esta venta?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-icon" title="Eliminar">🗑️</button>
                    </form>
                </div>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    @if($ventas->hasPages())
    <div class="pag-wrap">{{ $ventas->links() }}</div>
    @endif
    @else
    <div class="empty-state">
        <div class="empty-icon">🛒</div>
        <p>No hay ventas registradas aún.</p>
    </div>
    @endif
</div>

{{-- Create Venta Modal --}}
<div class="modal-overlay" id="modal-create">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('modal-create')">✕</button>
        <div class="modal-title">🛒 Nueva Venta</div>
        <form method="POST" action="{{ route('ventas.store') }}" id="venta-form">
            @csrf

            {{-- Product rows --}}
            <div class="form-group">
                <label>Productos *</label>
                <div class="product-rows" id="product-rows">
                    <div class="product-row">
                        <select name="productos[]" required>
                            <option value="">Seleccionar producto...</option>
                            @foreach($productos as $p)
                            <option value="{{ $p->id_producto }}" data-precio="{{ $p->precio }}">
                                {{ $p->nombre }} — ${{ number_format($p->precio, 2) }} (stock: {{ $p->stock_actual }})
                            </option>
                            @endforeach
                        </select>
                        <input type="number" name="cantidades[]" min="1" value="1" placeholder="Cant.">
                        <button type="button" class="btn-remove-row" onclick="removeRow(this)" title="Quitar">✕</button>
                    </div>
                </div>
                <button type="button" class="btn-add-row" onclick="addRow()">+ Agregar producto</button>
            </div>

            <div class="total-display" id="total-display">Total estimado: $0.00</div>

            <div class="form-group">
                <label>Cliente (opcional)</label>
                <select name="id_cliente">
                    <option value="">Público General</option>
                    @foreach($clientes as $c)
                    <option value="{{ $c->id_cliente }}">{{ $c->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Método de Pago *</label>
                    <select name="metodo_pago" required>
                        <option value="efectivo">💵 Efectivo</option>
                        <option value="tarjeta">💳 Tarjeta</option>
                        <option value="transferencia">🏦 Transferencia</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Estado *</label>
                    <select name="estado" required>
                        <option value="completada">✅ Completada</option>
                        <option value="pendiente">⏳ Pendiente</option>
                        <option value="cancelada">❌ Cancelada</option>
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-secondary" onclick="closeModal('modal-create')">Cancelar</button>
                <button type="submit" class="btn-primary">Registrar Venta</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const productosData = {
    @foreach($productos as $p)
    {{ $p->id_producto }}: { nombre: '{{ addslashes($p->nombre) }}', precio: {{ $p->precio }}, stock: {{ $p->stock_actual }} },
    @endforeach
};

// Actualizar stock disponible en el modal de nueva venta al cambiar producto
function updateStockDisplay(selectEl) {
    const pid = parseInt(selectEl.value);
    const row = selectEl.closest('.product-row');
    let stockSpan = row.querySelector('.stock-live');
    if (!stockSpan) {
        stockSpan = document.createElement('small');
        stockSpan.className = 'stock-live';
        stockSpan.style.cssText = 'color:#6b7280;font-size:.75rem;display:block;margin-top:.15rem;';
        selectEl.parentNode.insertBefore(stockSpan, selectEl.nextSibling);
    }
    if (pid && productosData[pid]) {
        stockSpan.textContent = 'Stock disponible: ' + productosData[pid].stock;
        stockSpan.style.color = productosData[pid].stock > 0 ? '#16a34a' : '#ef4444';
    } else {
        stockSpan.textContent = '';
    }
}

function openModal(id)  { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

function addRow() {
    const container = document.getElementById('product-rows');
    const firstRow  = container.querySelector('.product-row');
    const newRow    = firstRow.cloneNode(true);
    newRow.querySelectorAll('select')[0].selectedIndex = 0;
    newRow.querySelectorAll('input')[0].value = 1;
    container.appendChild(newRow);
    bindRowEvents();
    calcTotal();
}

function removeRow(btn) {
    const rows = document.querySelectorAll('.product-row');
    if (rows.length <= 1) { alert('Debe haber al menos un producto.'); return; }
    btn.closest('.product-row').remove();
    calcTotal();
}

function calcTotal() {
    let total = 0;
    document.querySelectorAll('.product-row').forEach(row => {
        const sel = row.querySelector('select');
        const qty = parseInt(row.querySelector('input').value) || 0;
        const pid = parseInt(sel.value);
        if (pid && productosData[pid]) {
            total += productosData[pid].precio * qty;
        }
    });
    document.getElementById('total-display').textContent = 'Total estimado: $' + total.toFixed(2);
}

function bindRowEvents() {
    document.querySelectorAll('.product-row select').forEach(sel => {
        sel.onchange = function() { calcTotal(); updateStockDisplay(this); };
        // Mostrar stock al abrir modal si ya tiene valor
        if (sel.value) updateStockDisplay(sel);
    });
    document.querySelectorAll('.product-row input[type=number]').forEach(inp => {
        inp.oninput = calcTotal;
    });
}

bindRowEvents();

function filterTable() {
    const q = document.getElementById('search-input').value.toLowerCase();
    document.querySelectorAll('#ventas-table tbody tr').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}

document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open'); });
});
</script>
@endpush
