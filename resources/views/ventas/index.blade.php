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

    /* ══════════ MODAL NUEVA VENTA (REDESIGNED POS) ══════════ */
    .modal-overlay {
        display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.65);
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        z-index: 200; align-items: center; justify-content: center;
        padding: 1.5rem;
    }
    .modal-overlay.open { display: flex; animation: fadeInModal .25s ease-out; }
    @keyframes fadeInModal {
        from { opacity: 0; transform: scale(0.97); }
        to { opacity: 1; transform: scale(1); }
    }

    .modal-venta-box {
        background: #ffffff;
        border-radius: 24px;
        padding: 2.2rem 2.4rem;
        width: 100%;
        max-width: 680px;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25), 0 0 0 1px rgba(226, 232, 240, 0.8);
        max-height: 90vh;
        overflow-y: auto;
    }

    /* Modal Header */
    .modal-header-pos {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding-bottom: 1.4rem;
        border-bottom: 1px solid var(--border-subtle);
        margin-bottom: 1.4rem;
        position: relative;
    }
    .mhp-icon-wrap {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: linear-gradient(135deg, #0b4e37 0%, #159c6c 100%);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(16, 111, 78, 0.25);
        flex-shrink: 0;
    }
    .mhp-title {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--text-dark);
        letter-spacing: -0.02em;
        line-height: 1.2;
    }
    .mhp-subtitle {
        font-size: 0.82rem;
        color: var(--text-muted);
        margin-top: 0.15rem;
    }
    .modal-close-pos {
        position: absolute;
        top: 0;
        right: 0;
        background: #f1f5f9;
        border: none;
        border-radius: 50%;
        width: 36px;
        height: 36px;
        cursor: pointer;
        font-size: 0.95rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .modal-close-pos:hover {
        background: #fee2e2;
        color: #ef4444;
        transform: rotate(90deg);
    }

    /* POS Products Container */
    .pos-section {
        background: #f8fafc;
        border: 1px solid var(--border-subtle);
        border-radius: 18px;
        padding: 1.2rem;
        margin-bottom: 1.4rem;
    }
    .pos-section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.85rem;
    }
    .pos-sec-title {
        font-size: 0.82rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--text-dark);
    }
    .pos-sec-hint {
        font-size: 0.78rem;
        font-weight: 700;
        color: #106f4e;
        background: #ecfdf5;
        padding: 0.2rem 0.65rem;
        border-radius: 20px;
        border: 1px solid #a7f3d0;
    }

    /* Product row item card */
    .pos-items-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    .pos-item-card {
        background: #ffffff;
        border: 1.5px solid var(--border-subtle);
        border-radius: 14px;
        padding: 0.85rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.03);
    }
    .pos-item-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.05);
    }
    .pic-prod-col {
        flex: 1;
        min-width: 0;
    }
    .pos-select-product {
        width: 100%;
        padding: 0.55rem 0.8rem;
        border: 1.5px solid var(--border-subtle);
        border-radius: 10px;
        font-family: inherit;
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--text-dark);
        background: #ffffff;
        outline: none;
        transition: all 0.2s;
    }
    .pos-select-product:focus {
        border-color: #159c6c;
        box-shadow: 0 0 0 3px rgba(21, 156, 108, 0.15);
    }
    .stock-live-pill {
        margin-top: 0.35rem;
        font-size: 0.75rem;
        font-weight: 700;
        min-height: 16px;
    }

    /* Stepper */
    .pic-qty-col {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 105px;
        flex-shrink: 0;
    }
    .qty-stepper {
        display: flex;
        align-items: center;
        background: #f1f5f9;
        border: 1.5px solid var(--border-subtle);
        border-radius: 10px;
        overflow: hidden;
        width: 100%;
    }
    .btn-qty-step {
        width: 32px;
        height: 36px;
        background: #f1f5f9;
        border: none;
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--text-dark);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.15s;
    }
    .btn-qty-step:hover {
        background: #e2e8f0;
    }
    .pos-qty-input {
        flex: 1;
        width: 100%;
        min-width: 0;
        border: none;
        background: transparent;
        text-align: center;
        font-weight: 800;
        font-size: 0.95rem;
        color: var(--text-dark);
        outline: none;
        padding: 0;
        -moz-appearance: textfield;
    }
    .pos-qty-input::-webkit-outer-spin-button,
    .pos-qty-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Subtotal Column */
    .pic-subtotal-col {
        width: 110px;
        text-align: right;
        flex-shrink: 0;
    }
    .row-subtotal-label {
        font-size: 0.68rem;
        color: var(--text-muted);
        text-transform: uppercase;
        font-weight: 700;
        display: block;
    }
    .row-subtotal-text {
        font-size: 1.05rem;
        font-weight: 800;
        color: #106f4e;
        font-family: 'Outfit', sans-serif;
    }

    /* Delete Button */
    .pic-action-col {
        flex-shrink: 0;
    }
    .btn-remove-item {
        background: #fff;
        color: #94a3b8;
        border: 1.5px solid var(--border-subtle);
        border-radius: 10px;
        width: 36px;
        height: 36px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        transition: all 0.2s ease;
    }
    .btn-remove-item:hover {
        background: #fff1f2;
        border-color: #fca5a5;
        color: #e11d48;
        transform: scale(1.05);
    }

    /* Add Row Button */
    .btn-add-item-pos {
        width: 100%;
        margin-top: 0.85rem;
        padding: 0.65rem 1rem;
        border-radius: 12px;
        border: 2px dashed #cbd5e1;
        background: #ffffff;
        color: var(--primary-700);
        font-family: inherit;
        font-size: 0.86rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }
    .btn-add-item-pos:hover {
        border-color: #159c6c;
        background: #ecfdf5;
        color: #065f46;
    }
    .bip-icon {
        font-size: 1.15rem;
        font-weight: 800;
        line-height: 1;
    }

    /* Checkout Total Box */
    .pos-checkout-bar {
        background: linear-gradient(135deg, #042217 0%, #0b4e37 100%);
        border-radius: 18px;
        padding: 1.2rem 1.6rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.3rem;
        box-shadow: 0 8px 20px rgba(4, 34, 23, 0.25);
    }
    .pcb-left {
        display: flex;
        flex-direction: column;
    }
    .pcb-label {
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #a7f3d0;
    }
    .pcb-sub {
        font-size: 0.78rem;
        color: #d1fae5;
        opacity: 0.85;
        margin-top: 0.1rem;
    }
    .pcb-right {
        display: flex;
        align-items: baseline;
        gap: 0.35rem;
        color: #ffffff;
    }
    .pcb-currency {
        font-size: 1.4rem;
        font-weight: 800;
        color: #6ee7b7;
    }
    .pcb-amount {
        font-size: 2.2rem;
        font-weight: 900;
        color: #ffffff;
        font-family: 'Outfit', sans-serif;
        letter-spacing: -0.03em;
        line-height: 1;
    }

    /* Meta Grid */
    .pos-meta-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr 1fr;
        gap: 0.85rem;
        margin-bottom: 1.4rem;
    }
    .form-group-pos {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }
    .form-group-pos label {
        font-size: 0.78rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #475569;
    }
    .pos-input-meta {
        width: 100%;
        padding: 0.6rem 0.8rem;
        border: 1.5px solid var(--border-subtle);
        border-radius: 12px;
        font-family: inherit;
        font-size: 0.88rem;
        font-weight: 600;
        color: var(--text-dark);
        background: #ffffff;
        outline: none;
        transition: all 0.2s;
    }
    .pos-input-meta:focus {
        border-color: #159c6c;
        box-shadow: 0 0 0 3px rgba(21, 156, 108, 0.15);
    }

    /* Modal Footer */
    .modal-footer-pos {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.85rem;
        padding-top: 1rem;
        border-top: 1px solid var(--border-subtle);
    }
    .btn-pos-cancel {
        padding: 0.7rem 1.4rem;
        border-radius: 12px;
        border: 1.5px solid var(--border-subtle);
        background: #ffffff;
        color: #64748b;
        font-family: inherit;
        font-size: 0.88rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-pos-cancel:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: var(--text-dark);
    }
    .btn-pos-submit {
        background: linear-gradient(135deg, #0b4e37 0%, #159c6c 100%);
        color: #ffffff;
        border: none;
        border-radius: 12px;
        padding: 0.7rem 1.8rem;
        font-family: inherit;
        font-size: 0.92rem;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        box-shadow: 0 4px 14px rgba(16, 111, 78, 0.35);
        transition: all 0.25s ease;
    }
    .btn-pos-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 111, 78, 0.45);
        filter: brightness(1.05);
    }

    @media (max-width: 640px) {
        .modal-venta-box { padding: 1.4rem; }
        .pos-meta-grid { grid-template-columns: 1fr; }
        .pos-item-card { flex-wrap: wrap; }
        .pic-subtotal-col { text-align: left; }
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

{{-- Create Venta Modal (Redesigned POS) --}}
<div class="modal-overlay" id="modal-create">
    <div class="modal-box modal-venta-box">
        <!-- Header -->
        <div class="modal-header-pos">
            <div class="mhp-icon-wrap">🛒</div>
            <div>
                <h2 class="mhp-title">Nueva Venta en Caja</h2>
                <p class="mhp-subtitle">Punto de Venta (POS) · Selecciona los productos y método de cobro</p>
            </div>
            <button type="button" class="modal-close-pos" onclick="closeModal('modal-create')" title="Cerrar ventana">✕</button>
        </div>

        <form method="POST" action="{{ route('ventas.store') }}" id="venta-form">
            @csrf

            <!-- Section: Productos -->
            <div class="pos-section">
                <div class="pos-section-head">
                    <span class="pos-sec-title">Artículos de la Venta</span>
                    <span class="pos-sec-hint" id="items-counter">1 producto</span>
                </div>

                <div class="pos-items-list" id="product-rows">
                    <div class="pos-item-card product-row">
                        <div class="pic-prod-col">
                            <select name="productos[]" class="pos-select-product" required onchange="handleProductChange(this)">
                                <option value="">Seleccionar producto del catálogo...</option>
                                @foreach($productos as $p)
                                @php $isLow = $p->stock_actual <= $p->stock_minimo; @endphp
                                <option value="{{ $p->id_producto }}" data-precio="{{ $p->precio }}">
                                    {{ $p->nombre }} — ${{ number_format($p->precio, 0, ',', '.') }} @if($isLow) [⚠️ Stock bajo: {{ $p->stock_actual }}] @else (stock: {{ $p->stock_actual }}) @endif
                                </option>
                                @endforeach
                            </select>
                            <div class="stock-live-pill"></div>
                        </div>

                        <div class="pic-qty-col">
                            <div class="qty-stepper">
                                <button type="button" class="btn-qty-step" onclick="stepQty(this, -1)">−</button>
                                <input type="number" name="cantidades[]" class="pos-qty-input" min="1" value="1" oninput="calcTotal()" required>
                                <button type="button" class="btn-qty-step" onclick="stepQty(this, 1)">+</button>
                            </div>
                        </div>

                        <div class="pic-subtotal-col">
                            <span class="row-subtotal-label">Subtotal</span>
                            <span class="row-subtotal-text">$0</span>
                        </div>

                        <div class="pic-action-col">
                            <button type="button" class="btn-remove-item" onclick="removeRow(this)" title="Quitar producto">
                                🗑️
                            </button>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn-add-item-pos" onclick="addRow()">
                    <span class="bip-icon">+</span>
                    <span>Agregar otro producto</span>
                </button>
            </div>

            <!-- Section: Resumen de Cobro -->
            <div class="pos-checkout-bar">
                <div class="pcb-left">
                    <span class="pcb-label">Total a Cobrar</span>
                    <span class="pcb-sub" id="pcb-count-text">0 artículos agregados</span>
                </div>
                <div class="pcb-right">
                    <span class="pcb-currency">$</span>
                    <span class="pcb-amount" id="total-amount-display">0</span>
                </div>
            </div>

            <!-- Section: Detalles de la Transacción -->
            <div class="pos-meta-grid">
                <div class="form-group-pos">
                    <label>👤 Cliente</label>
                    <select name="id_cliente" class="pos-input-meta">
                        <option value="">Público General</option>
                        @foreach($clientes as $c)
                        <option value="{{ $c->id_cliente }}">{{ $c->nombre }} ({{ $c->documento ?: 'Sin doc.' }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group-pos">
                    <label>💳 Método de Pago *</label>
                    <select name="metodo_pago" class="pos-input-meta" required>
                        <option value="efectivo">💵 Efectivo</option>
                        <option value="tarjeta">💳 Tarjeta (Débito / Crédito)</option>
                        <option value="transferencia">🏦 Transferencia Bancaria</option>
                    </select>
                </div>

                <div class="form-group-pos">
                    <label>📌 Estado *</label>
                    <select name="estado" class="pos-input-meta" required>
                        <option value="completada">✅ Completada</option>
                        <option value="pendiente">⏳ Pendiente</option>
                        <option value="cancelada">❌ Cancelada</option>
                    </select>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer-pos">
                <button type="button" class="btn-pos-cancel" onclick="closeModal('modal-create')">Cancelar</button>
                <button type="submit" class="btn-pos-submit">
                    <span>🛒</span>
                    <span>Confirmar y Cobrar</span>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
const productosData = {
    @foreach($productos as $p)
    {{ $p->id_producto }}: { 
        nombre: '{{ addslashes($p->nombre) }}', 
        precio: {{ $p->precio }}, 
        stock: {{ $p->stock_actual }},
        min: {{ $p->stock_minimo }}
    },
    @endforeach
};

function formatMoney(num) {
    return Number(num).toLocaleString('es-CO');
}

function handleProductChange(selectEl) {
    updateStockDisplay(selectEl);
    calcTotal();
}

function updateStockDisplay(selectEl) {
    const pid = parseInt(selectEl.value);
    const row = selectEl.closest('.product-row');
    const pill = row.querySelector('.stock-live-pill');
    if (!pill) return;

    if (pid && productosData[pid]) {
        const prod = productosData[pid];
        if (prod.stock <= prod.min) {
            pill.innerHTML = `<span style="color:#b45309;background:#fef3c7;border:1px solid #fde68a;padding:2px 8px;border-radius:6px;display:inline-block;">⚠️ Stock bajo: ${prod.stock} disponibles (mín: ${prod.min})</span>`;
        } else {
            pill.innerHTML = `<span style="color:#065f46;background:#ecfdf5;border:1px solid #a7f3d0;padding:2px 8px;border-radius:6px;display:inline-block;">✅ Stock disponible: ${prod.stock}</span>`;
        }
    } else {
        pill.innerHTML = '';
    }
}

function stepQty(btn, delta) {
    const row = btn.closest('.product-row');
    const input = row.querySelector('.pos-qty-input');
    let current = parseInt(input.value) || 1;
    let nextVal = Math.max(1, current + delta);
    input.value = nextVal;
    calcTotal();
}

function openModal(id)  { 
    document.getElementById(id).classList.add('open'); 
    calcTotal();
}
function closeModal(id) { 
    document.getElementById(id).classList.remove('open'); 
}

function addRow() {
    const container = document.getElementById('product-rows');
    const firstRow  = container.querySelector('.product-row');
    const newRow    = firstRow.cloneNode(true);
    
    // Reset inputs in cloned row
    const sel = newRow.querySelector('select');
    sel.selectedIndex = 0;
    newRow.querySelector('.pos-qty-input').value = 1;
    newRow.querySelector('.stock-live-pill').innerHTML = '';
    newRow.querySelector('.row-subtotal-text').textContent = '$0';
    
    container.appendChild(newRow);
    calcTotal();
}

function removeRow(btn) {
    const rows = document.querySelectorAll('.product-row');
    if (rows.length <= 1) { 
        alert('Debe haber al menos un producto en la venta.'); 
        return; 
    }
    btn.closest('.product-row').remove();
    calcTotal();
}

function calcTotal() {
    let total = 0;
    let totalItems = 0;
    const rows = document.querySelectorAll('.product-row');

    rows.forEach(row => {
        const sel = row.querySelector('.pos-select-product');
        const qtyInp = row.querySelector('.pos-qty-input');
        const subtotalText = row.querySelector('.row-subtotal-text');
        const qty = parseInt(qtyInp.value) || 0;
        const pid = parseInt(sel.value);

        if (pid && productosData[pid]) {
            const subtotal = productosData[pid].precio * qty;
            total += subtotal;
            totalItems += qty;
            if (subtotalText) subtotalText.textContent = '$' + formatMoney(subtotal);
        } else {
            if (subtotalText) subtotalText.textContent = '$0';
        }
    });

    const totalDisplay = document.getElementById('total-amount-display');
    if (totalDisplay) totalDisplay.textContent = formatMoney(total);

    const countText = document.getElementById('pcb-count-text');
    if (countText) countText.textContent = totalItems + (totalItems === 1 ? ' artículo agregado' : ' artículos agregados');

    const counterBadge = document.getElementById('items-counter');
    if (counterBadge) counterBadge.textContent = rows.length + (rows.length === 1 ? ' producto' : ' productos');
}

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
