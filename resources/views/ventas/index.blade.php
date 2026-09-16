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

    .stats-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 1.2rem; margin-bottom: 1.8rem; }
    .stat-card {
        border-radius: 14px; padding: 1.2rem 1.4rem;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); display: flex; align-items: center; gap: 1rem;
    }
    .stat-card.green  { background: #d1fae5; }
    .stat-card.blue   { background: #dbeafe; }
    .stat-card.teal   { background: #ccfbf1; }
    .stat-card.purple { background: #ede9fe; }
    .stat-card .sc-icon { font-size: 1.8rem; }
    .stat-card .sc-num  { font-size: 1.6rem; font-weight: 900; color: #111827; }
    .stat-card .sc-label { font-size: .85rem; font-weight: 600; color: #374151; }

    .card { background: #fff; border-radius: 14px; box-shadow: 0 2px 12px rgba(0,0,0,.06); overflow: hidden; }
    .card-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 1rem 1.4rem; border-bottom: 1px solid #f3f4f6;
    }
    .card-header h2 { font-size: 1rem; font-weight: 700; color: #111827; }
    .search-wrap input {
        background: #f3f4f6; border: none; border-radius: 8px;
        padding: .45rem .9rem; font-family: inherit; font-size: .85rem; width: 220px; outline: none;
    }

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
    .badge-completada { background: #d1fae5; color: #065f46; }
    .badge-pendiente  { background: #fef9c3; color: #92400e; }
    .badge-cancelada  { background: #fee2e2; color: #b91c1c; }

    .empty-state { text-align: center; padding: 3rem; color: #9ca3af; }
    .empty-state .empty-icon { font-size: 3rem; margin-bottom: .7rem; }

    .actions { display: flex; gap: .4rem; }
    .btn-icon {
        width: 30px; height: 30px; border-radius: 7px; border: none; cursor: pointer;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: .85rem; background: #fee2e2; color: #b91c1c;
    }
    .btn-icon:hover { transform: scale(1.1); }

    .pag-wrap { padding: .8rem 1.4rem; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end; }

    /* Modal */
    .modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(0,0,0,.45);
        z-index: 200; align-items: center; justify-content: center;
    }
    .modal-overlay.open { display: flex; }
    .modal-box {
        background: #fff; border-radius: 16px; padding: 2rem;
        width: 100%; max-width: 560px; position: relative;
        box-shadow: 0 20px 60px rgba(0,0,0,.2); max-height: 92vh; overflow-y: auto;
    }
    .modal-title { font-size: 1.1rem; font-weight: 800; color: #111827; margin-bottom: 1.2rem; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: .8rem; }
    .form-group { margin-bottom: .9rem; }
    .form-group label { display: block; font-size: .8rem; font-weight: 600; color: #374151; margin-bottom: .3rem; }
    .form-group input, .form-group select {
        width: 100%; padding: .55rem .8rem; border: 1.5px solid #e5e7eb; border-radius: 8px;
        font-family: inherit; font-size: .88rem; outline: none;
    }
    .form-group input:focus, .form-group select:focus { border-color: #16a34a; box-shadow: 0 0 0 2px #bbf7d0; }
    .modal-footer { display: flex; gap: .7rem; justify-content: flex-end; margin-top: .5rem; }
    .modal-close {
        position: absolute; top: 1rem; right: 1rem; background: #f3f4f6; border: none;
        border-radius: 7px; width: 28px; height: 28px; cursor: pointer; font-size: .95rem;
    }
    .modal-close:hover { background: #fee2e2; color: #b91c1c; }

    /* Product rows in venta */
    .product-rows { border: 1.5px solid #e5e7eb; border-radius: 10px; padding: .8rem; margin-bottom: .5rem; }
    .product-row { display: grid; grid-template-columns: 1fr 80px 28px; gap: .5rem; align-items: center; margin-bottom: .5rem; }
    .product-row:last-child { margin-bottom: 0; }
    .product-row select, .product-row input {
        padding: .4rem .6rem; border: 1.5px solid #e5e7eb; border-radius: 7px;
        font-family: inherit; font-size: .83rem; outline: none;
    }
    .btn-remove-row {
        background: #fee2e2; color: #b91c1c; border: none; border-radius: 6px;
        width: 28px; height: 28px; cursor: pointer; font-size: .9rem;
        display: flex; align-items: center; justify-content: center;
    }
    .btn-add-row {
        background: #d1fae5; color: #065f46; border: none; border-radius: 7px;
        padding: .35rem .8rem; font-family: inherit; font-size: .82rem; font-weight: 700;
        cursor: pointer; margin-top: .3rem;
    }
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
