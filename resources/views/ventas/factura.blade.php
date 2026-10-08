@extends('layouts.sidebar')

@section('title', 'Factura de Venta #' . str_pad($venta->id_venta, 6, '0', STR_PAD_LEFT))
@section('page-title', 'Factura de Venta')
@section('page-subtitle', 'Comprobante oficial de venta y cobro en caja')

@push('styles')
<style>
    /* ══════════ TOOLBAR SUPERIOR ══════════ */
    .factura-actions-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #ffffff;
        border: 1px solid var(--border-subtle);
        border-radius: 16px;
        padding: 1rem 1.4rem;
        margin-bottom: 1.8rem;
        box-shadow: var(--shadow-sm);
        gap: 1rem;
        flex-wrap: wrap;
    }
    .fab-left {
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }
    .fab-right {
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }

    .btn-fab {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.1rem;
        border-radius: 10px;
        font-family: inherit;
        font-size: 0.88rem;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s ease;
        border: 1.5px solid transparent;
    }
    .btn-fab-back {
        background: #f1f5f9;
        color: #475569;
        border-color: #cbd5e1;
    }
    .btn-fab-back:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .btn-fab-print {
        background: #0b4e37;
        color: #ffffff;
        box-shadow: 0 3px 10px rgba(11, 78, 55, 0.25);
    }
    .btn-fab-print:hover {
        background: #063826;
        transform: translateY(-1px);
    }
    .btn-fab-pdf {
        background: #2563eb;
        color: #ffffff;
        box-shadow: 0 3px 10px rgba(37, 99, 235, 0.25);
    }
    .btn-fab-pdf:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }
    .btn-fab-new {
        background: #106f4e;
        color: #ffffff;
    }
    .btn-fab-new:hover {
        background: #0b4e37;
    }

    /* ══════════ HOJA DE FACTURA (DOCUMENTO) ══════════ */
    .factura-sheet-wrap {
        display: flex;
        justify-content: center;
        margin-bottom: 3rem;
    }
    .factura-sheet {
        background: #ffffff;
        width: 100%;
        max-width: 820px;
        border-radius: 20px;
        border: 1px solid var(--border-subtle);
        box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        padding: 2.8rem 3rem;
        position: relative;
        overflow: hidden;
    }
    .factura-sheet::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 6px;
        background: linear-gradient(90deg, #0b4e37 0%, #159c6c 50%, #f59e0b 100%);
    }

    /* Header del documento */
    .factura-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding-bottom: 2rem;
        border-bottom: 2px dashed #e2e8f0;
        margin-bottom: 1.8rem;
        gap: 1.5rem;
    }
    .fh-brand {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .fh-logo-icon {
        width: 54px;
        height: 54px;
        background: linear-gradient(135deg, #0b4e37 0%, #159c6c 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.7rem;
        color: #fff;
        box-shadow: 0 4px 14px rgba(16, 111, 78, 0.25);
        flex-shrink: 0;
    }
    .fh-brand-info h1 {
        font-size: 1.45rem;
        font-weight: 900;
        color: #042217;
        letter-spacing: -0.02em;
        line-height: 1.1;
    }
    .fh-brand-info h1 span {
        color: #159c6c;
    }
    .fh-legal-text {
        font-size: 0.78rem;
        color: #64748b;
        margin-top: 0.35rem;
        line-height: 1.4;
    }

    .fh-doc-box {
        text-align: right;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 1rem 1.4rem;
        min-width: 240px;
    }
    .fdb-badge {
        font-size: 0.72rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #106f4e;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        padding: 0.2rem 0.65rem;
        border-radius: 20px;
        display: inline-block;
        margin-bottom: 0.4rem;
    }
    .fdb-num {
        font-family: 'Outfit', sans-serif;
        font-size: 1.35rem;
        font-weight: 900;
        color: #0f172a;
        line-height: 1.1;
    }
    .fdb-date {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
        margin-top: 0.3rem;
    }

    /* Grid de Metadatos: Cliente y Venta */
    .factura-meta-grid {
        display: grid;
        grid-template-columns: 1.2fr 1fr;
        gap: 1.5rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.3rem 1.5rem;
        margin-bottom: 2rem;
    }
    .fmg-block h3 {
        font-size: 0.78rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #0b4e37;
        margin-bottom: 0.7rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .fmg-list {
        list-style: none;
        font-size: 0.86rem;
        color: #334155;
    }
    .fmg-list li {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.35rem;
    }
    .fmg-list li strong {
        color: #64748b;
        font-weight: 600;
        font-size: 0.82rem;
    }
    .fmg-list li span {
        font-weight: 700;
        color: #0f172a;
        text-align: right;
    }

    /* Tabla de Ítems */
    .factura-items-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-bottom: 1.8rem;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
    }
    .factura-items-table thead th {
        background: #042217;
        color: #ffffff;
        font-size: 0.76rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        padding: 0.85rem 1rem;
        text-align: left;
    }
    .factura-items-table thead th:last-child {
        text-align: right;
    }
    .factura-items-table tbody td {
        padding: 0.85rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
        color: #1e293b;
    }
    .factura-items-table tbody tr:last-child td {
        border-bottom: none;
    }
    .factura-items-table tbody tr:nth-child(even) {
        background: #fafafa;
    }
    .fit-prod-code {
        font-size: 0.76rem;
        font-weight: 700;
        color: #64748b;
        display: block;
    }
    .fit-prod-name {
        font-weight: 700;
        color: #0f172a;
    }
    .fit-qty-badge {
        background: #f1f5f9;
        font-weight: 800;
        padding: 0.25rem 0.65rem;
        border-radius: 6px;
        font-size: 0.84rem;
        display: inline-block;
    }

    /* Sección de Totales */
    .factura-totals-wrap {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 2rem;
    }
    .factura-totals-box {
        width: 320px;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 1.2rem 1.4rem;
    }
    .ft-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.88rem;
        margin-bottom: 0.55rem;
        color: #475569;
    }
    .ft-row strong {
        font-weight: 700;
        color: #0f172a;
    }
    .ft-divider {
        height: 1px;
        background: #cbd5e1;
        margin: 0.75rem 0;
    }
    .ft-total-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        padding-top: 0.35rem;
    }
    .ftt-label {
        font-size: 1rem;
        font-weight: 900;
        color: #042217;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .ftt-val {
        font-family: 'Outfit', sans-serif;
        font-size: 1.7rem;
        font-weight: 900;
        color: #106f4e;
        line-height: 1;
    }

    /* Pie legal de la Factura */
    .factura-foot {
        border-top: 1.5px dashed #cbd5e1;
        padding-top: 1.4rem;
        margin-top: 1.4rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
    }
    .ff-legal {
        font-size: 0.76rem;
        color: #64748b;
        line-height: 1.45;
        flex: 1;
    }
    .ff-legal p { margin-bottom: 0.25rem; }
    .ff-qr-box {
        width: 80px;
        height: 80px;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 5px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        flex-shrink: 0;
    }
    .ff-qr-box img {
        width: 100%;
        height: auto;
    }
    .ff-qr-sub {
        font-size: 0.62rem;
        font-weight: 800;
        color: #64748b;
        margin-top: 2px;
        letter-spacing: 0.05em;
    }

    /* ══════════ ESTILOS DE IMPRESIÓN (CTRL + P) ══════════ */
    @media print {
        body {
            background: #ffffff !important;
            color: #000000 !important;
        }
        .sidebar, .page-header-bar, .factura-actions-bar, .btn-logout-side {
            display: none !important;
        }
        .main-panel {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }
        .factura-sheet-wrap {
            margin: 0 !important;
            padding: 0 !important;
        }
        .factura-sheet {
            border: none !important;
            box-shadow: none !important;
            border-radius: 0 !important;
            padding: 0 !important;
            max-width: 100% !important;
        }
        .factura-sheet::before {
            display: none;
        }
        @page {
            size: a4 portrait;
            margin: 12mm 15mm 12mm 15mm;
        }
    }
</style>
@endpush

@section('content')

@php
    $subtotalSinIva = $venta->total / 1.19;
    $iva19 = $venta->total - $subtotalSinIva;
    $numFactura = 'FACT-' . str_pad($venta->id_venta, 6, '0', STR_PAD_LEFT);
@endphp

<!-- Barra de Acciones de la Factura -->
<div class="factura-actions-bar">
    <div class="fab-left">
        <a href="{{ route('ventas.index') }}" class="btn-fab btn-fab-back">
            <span>←</span>
            <span>Volver a Ventas</span>
        </a>
        <span style="color:#cbd5e1;">|</span>
        <strong style="color:var(--text-dark);font-size:0.95rem;">Comprobante de Venta</strong>
    </div>

    <div class="fab-right">
        <button type="button" onclick="window.print()" class="btn-fab btn-fab-print" title="Imprimir comprobante físico">
            <span>🖨️</span>
            <span>Imprimir Factura</span>
        </button>

        <a href="{{ route('ventas.factura.pdf', $venta->id_venta) }}" class="btn-fab btn-fab-pdf" title="Descargar archivo PDF oficial">
            <span>📥</span>
            <span>Descargar PDF</span>
        </a>

        <a href="{{ route('ventas.index') }}" class="btn-fab btn-fab-new" title="Registrar otra venta en caja">
            <span>🛒</span>
            <span>Nueva Venta</span>
        </a>
    </div>
</div>

<!-- Hoja de Factura Oficial -->
<div class="factura-sheet-wrap">
    <div class="factura-sheet">
        <!-- Encabezado de Factura -->
        <div class="factura-head">
            <div class="fh-brand">
                <div class="fh-logo-icon">🛒</div>
                <div class="fh-brand-info">
                    <h1>Super<b>Fresco</b></h1>
                    <div class="fh-legal-text">
                        <strong>SUPERFRESCO S.A.S.</strong> · NIT: 900.852.147-3<br>
                        Calle Principal #10-24, Bogotá D.C., Colombia<br>
                        Tel: +57 (601) 555-0199 · Email: ventas@superfresco.com<br>
                        IVA Régimen Común · Responsable de IVA
                    </div>
                </div>
            </div>

            <div class="fh-doc-box">
                <div class="fdb-badge">FACTURA ELECTRÓNICA DE VENTA</div>
                <div class="fdb-num">{{ $numFactura }}</div>
                <div class="fdb-date">
                    📅 {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y - h:i A') }}
                </div>
                <div style="margin-top:0.4rem;">
                    <span class="badge 
                        @if($venta->estado === 'completada') badge-completada 
                        @elseif($venta->estado === 'pendiente') badge-pendiente 
                        @else badge-cancelada @endif" 
                        style="padding:3px 10px;font-size:0.75rem;border-radius:12px;">
                        Estado: {{ ucfirst($venta->estado) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Metadatos de la Venta (Cliente & Cajero) -->
        <div class="factura-meta-grid">
            <div class="fmg-block">
                <h3>👤 Datos del Adquiriente / Cliente</h3>
                <ul class="fmg-list">
                    <li>
                        <strong>Nombre / Razón Social:</strong>
                        <span>{{ $venta->cliente ? $venta->cliente->nombre : 'Consumidor Final (Público General)' }}</span>
                    </li>
                    <li>
                        <strong>Identificación / NIT / CC:</strong>
                        <span>{{ $venta->cliente && $venta->cliente->documento ? $venta->cliente->documento : '222222222222' }}</span>
                    </li>
                    <li>
                        <strong>Teléfono:</strong>
                        <span>{{ $venta->cliente && $venta->cliente->telefono ? $venta->cliente->telefono : '—' }}</span>
                    </li>
                    <li>
                        <strong>Dirección:</strong>
                        <span>{{ $venta->cliente && $venta->cliente->direccion ? $venta->cliente->direccion : 'Mostrador / Punto de Venta' }}</span>
                    </li>
                </ul>
            </div>

            <div class="fmg-block">
                <h3>💼 Información de la Transacción</h3>
                <ul class="fmg-list">
                    <li>
                        <strong>Cajero / Emisor:</strong>
                        <span>{{ $venta->usuario ? $venta->usuario->nombres . ' ' . $venta->usuario->apellidos : 'Cajero General' }}</span>
                    </li>
                    <li>
                        <strong>Método de Cobro:</strong>
                        <span>
                            @if($venta->metodo_pago === 'efectivo') 💵 Efectivo
                            @elseif($venta->metodo_pago === 'tarjeta') 💳 Tarjeta Débito/Crédito
                            @else 🏦 Transferencia
                            @endif
                        </span>
                    </li>
                    <li>
                        <strong>Moneda:</strong>
                        <span>COP ($ Pesos Colombianos)</span>
                    </li>
                    <li>
                        <strong>Total Artículos:</strong>
                        <span>{{ $venta->detalles->sum('cantidad') }} unidades</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Tabla de Productos Vendidos -->
        <table class="factura-items-table">
            <thead>
                <tr>
                    <th style="width: 12%;">Código</th>
                    <th>Descripción del Producto</th>
                    <th style="width: 12%; text-align: center;">Cant.</th>
                    <th style="width: 20%; text-align: right;">Precio Unitario</th>
                    <th style="width: 20%; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($venta->detalles as $det)
                <tr>
                    <td>
                        <span class="fit-prod-code">{{ $det->producto ? $det->producto->codigo : 'PROD-'.$det->id_producto }}</span>
                    </td>
                    <td>
                        <div class="fit-prod-name">{{ $det->producto ? $det->producto->nombre : 'Producto #' . $det->id_producto }}</div>
                        @if($det->producto && $det->producto->categoria)
                            <small style="color:#64748b;font-size:0.75rem;">{{ $det->producto->categoria->nombre }}</small>
                        @endif
                    </td>
                    <td style="text-align: center;">
                        <span class="fit-qty-badge">{{ $det->cantidad }}</span>
                    </td>
                    <td style="text-align: right; font-weight: 600;">
                        ${{ number_format($det->precio, 2) }}
                    </td>
                    <td style="text-align: right; font-weight: 800; color:#0f172a;">
                        ${{ number_format($det->subtotal, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Sección de Liquidación de Totales -->
        <div class="factura-totals-wrap">
            <div class="factura-totals-box">
                <div class="ft-row">
                    <span>Subtotal Base (sin IVA):</span>
                    <strong>${{ number_format($subtotalSinIva, 2) }}</strong>
                </div>
                <div class="ft-row">
                    <span>IVA Discriminado (19%):</span>
                    <strong>${{ number_format($iva19, 2) }}</strong>
                </div>
                <div class="ft-divider"></div>
                <div class="ft-total-row">
                    <span class="ftt-label">Total a Pagar</span>
                    <span class="ftt-val">${{ number_format($venta->total, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Pie de Factura Legal y Fiscal -->
        <div class="factura-foot">
            <div class="ff-legal">
                <p><strong>Autorización DIAN:</strong> Resolución de Facturación Electrónica N° 18760000001 de 2026. Prefijo FACT del 000001 al 999999. Vigencia: 24 meses.</p>
                <p><strong>CUFE:</strong> {{ hash('sha256', 'superfresco-' . $venta->id_venta . '-' . $venta->fecha_venta . '-' . $venta->total) }}</p>
                <p style="margin-top:0.35rem; color:#106f4e; font-weight:700;">¡Gracias por preferir a SuperFresco! Conserve este comprobante para cualquier garantía o devolución dentro de los 30 días siguientes.</p>
            </div>

            <div class="ff-qr-box">
                <!-- Código QR representativo en SVG -->
                <svg width="60" height="60" viewBox="0 0 100 100" fill="#042217" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0" y="0" width="30" height="30" />
                    <rect x="5" y="5" width="20" height="20" fill="#fff" />
                    <rect x="10" y="10" width="10" height="10" />
                    <rect x="70" y="0" width="30" height="30" />
                    <rect x="75" y="5" width="20" height="20" fill="#fff" />
                    <rect x="80" y="10" width="10" height="10" />
                    <rect x="0" y="70" width="30" height="30" />
                    <rect x="5" y="75" width="20" height="20" fill="#fff" />
                    <rect x="10" y="80" width="10" height="10" />
                    <rect x="40" y="10" width="20" height="10" />
                    <rect x="40" y="40" width="20" height="20" />
                    <rect x="70" y="40" width="10" height="20" />
                    <rect x="10" y="40" width="20" height="10" />
                    <rect x="40" y="70" width="20" height="10" />
                    <rect x="70" y="70" width="20" height="20" />
                </svg>
                <span class="ff-qr-sub">DIAN VERIF</span>
            </div>
        </div>
    </div>
</div>

@endsection
