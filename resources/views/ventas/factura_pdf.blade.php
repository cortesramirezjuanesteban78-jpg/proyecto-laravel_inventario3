<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura_{{ str_pad($venta->id_venta, 6, '0', STR_PAD_LEFT) }}_SuperFresco</title>
    <style>
        @page {
            margin: 15mm 15mm 15mm 15mm;
            size: a4 portrait;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Encabezado */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #106f4e;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .brand-title {
            font-size: 22px;
            font-weight: bold;
            color: #0b4e37;
            letter-spacing: -0.5px;
        }
        .brand-title span {
            color: #159c6c;
        }
        .brand-sub {
            font-size: 9px;
            color: #64748b;
            line-height: 1.35;
            margin-top: 4px;
        }
        .doc-badge {
            text-align: right;
            vertical-align: top;
        }
        .doc-badge-title {
            background-color: #ecfdf5;
            color: #065f46;
            font-size: 9px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 4px;
            border: 1px solid #a7f3d0;
            display: inline-block;
            margin-bottom: 4px;
        }
        .doc-num {
            font-size: 16px;
            font-weight: bold;
            color: #0f172a;
            margin: 2px 0;
        }
        .doc-date {
            font-size: 9px;
            color: #64748b;
        }

        /* Tabla de Información de Cliente y Transacción */
        .info-table {
            width: 100%;
            margin-bottom: 18px;
            border-collapse: separate;
            border-spacing: 10px 0;
        }
        .info-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 3px solid #106f4e;
            padding: 10px 12px;
            border-radius: 6px;
            vertical-align: top;
            width: 50%;
        }
        .info-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            color: #0b4e37;
            margin-bottom: 6px;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 3px;
        }
        .info-row {
            font-size: 9.5px;
            margin-bottom: 3px;
        }
        .info-row .lbl {
            color: #64748b;
            font-weight: bold;
            display: inline-block;
            width: 90px;
        }
        .info-row .val {
            color: #0f172a;
            font-weight: bold;
        }

        /* Tabla de Productos */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .items-table th {
            background-color: #042217;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 7px 8px;
            text-align: left;
        }
        .items-table td {
            padding: 8px;
            font-size: 10px;
            border-bottom: 1px solid #e2e8f0;
            color: #1e293b;
        }
        .items-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .code-col { width: 12%; font-family: monospace; font-size: 9px; color: #475569; }
        .desc-col { width: 44%; }
        .qty-col  { width: 10%; text-align: center; font-weight: bold; }
        .price-col { width: 17%; text-align: right; }
        .subt-col { width: 17%; text-align: right; font-weight: bold; }

        /* Liquidación de Totales */
        .totals-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .totals-box {
            width: 250px;
            float: right;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
            border-radius: 6px;
            padding: 8px 12px;
        }
        .t-row {
            font-size: 9.5px;
            padding: 3px 0;
            color: #475569;
        }
        .t-row .lbl {
            float: left;
        }
        .t-row .val {
            float: right;
            font-weight: bold;
            color: #0f172a;
        }
        .t-divider {
            border-bottom: 1px solid #cbd5e1;
            margin: 6px 0;
            clear: both;
        }
        .t-final {
            font-size: 13px;
            font-weight: bold;
            color: #0b4e37;
            clear: both;
            padding-top: 4px;
        }
        .t-final .lbl {
            float: left;
            text-transform: uppercase;
        }
        .t-final .val {
            float: right;
        }

        .clear {
            clear: both;
        }

        /* Pie de Página Legal */
        .footer-table {
            width: 100%;
            border-top: 1px dashed #cbd5e1;
            padding-top: 10px;
            margin-top: 15px;
        }
        .footer-legal {
            font-size: 8px;
            color: #64748b;
            line-height: 1.35;
        }
        .footer-badge {
            text-align: right;
            vertical-align: top;
            font-size: 8.5px;
            color: #0b4e37;
            font-weight: bold;
        }
    </style>
</head>
<body>

@php
    $subtotalSinIva = $venta->total / 1.19;
    $iva19 = $venta->total - $subtotalSinIva;
    $numFactura = 'FACT-' . str_pad($venta->id_venta, 6, '0', STR_PAD_LEFT);
@endphp

<!-- Encabezado -->
<table class="header-table">
    <tr>
        <td style="width: 60%; vertical-align: top;">
            <div class="brand-title">Super<span>Fresco</span></div>
            <div class="brand-sub">
                <strong>SUPERFRESCO S.A.S.</strong> · NIT: 900.852.147-3<br>
                Calle Principal #10-24, Bogotá D.C., Colombia<br>
                Tel: +57 (601) 555-0199 · Email: ventas@superfresco.com<br>
                IVA Régimen Común · Responsable de IVA
            </div>
        </td>
        <td class="doc-badge">
            <div class="doc-badge-title">FACTURA ELECTRÓNICA DE VENTA</div>
            <div class="doc-num">{{ $numFactura }}</div>
            <div class="doc-date">Fecha: {{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y - h:i A') }}</div>
            <div class="doc-date" style="margin-top: 3px; font-weight: bold; color: #0b4e37;">
                Estado: {{ strtoupper($venta->estado) }}
            </div>
        </td>
    </tr>
</table>

<!-- Metadatos de la Venta (Cliente & Cajero) -->
<table class="info-table">
    <tr>
        <td class="info-box">
            <div class="info-title">DATOS DEL ADQUIRIENTE / CLIENTE</div>
            <div class="info-row">
                <span class="lbl">Cliente:</span>
                <span class="val">{{ $venta->cliente ? $venta->cliente->nombre : 'Consumidor Final (Público General)' }}</span>
            </div>
            <div class="info-row">
                <span class="lbl">NIT / CC:</span>
                <span class="val">{{ $venta->cliente && $venta->cliente->documento ? $venta->cliente->documento : '222222222222' }}</span>
            </div>
            <div class="info-row">
                <span class="lbl">Teléfono:</span>
                <span class="val">{{ $venta->cliente && $venta->cliente->telefono ? $venta->cliente->telefono : '—' }}</span>
            </div>
            <div class="info-row">
                <span class="lbl">Dirección:</span>
                <span class="val">{{ $venta->cliente && $venta->cliente->direccion ? $venta->cliente->direccion : 'Punto de Venta' }}</span>
            </div>
        </td>

        <td class="info-box">
            <div class="info-title">DATOS DE LA TRANSACCIÓN</div>
            <div class="info-row">
                <span class="lbl">Cajero / Operador:</span>
                <span class="val">{{ $venta->usuario ? $venta->usuario->nombres . ' ' . $venta->usuario->apellidos : 'Cajero General' }}</span>
            </div>
            <div class="info-row">
                <span class="lbl">Método de Pago:</span>
                <span class="val">{{ strtoupper($venta->metodo_pago) }}</span>
            </div>
            <div class="info-row">
                <span class="lbl">Moneda:</span>
                <span class="val">COP ($ Pesos)</span>
            </div>
            <div class="info-row">
                <span class="lbl">Total Artículos:</span>
                <span class="val">{{ $venta->detalles->sum('cantidad') }} unidades</span>
            </div>
        </td>
    </tr>
</table>

<!-- Tabla de Productos -->
<table class="items-table">
    <thead>
        <tr>
            <th class="code-col">Código</th>
            <th class="desc-col">Descripción</th>
            <th class="qty-col">Cant.</th>
            <th class="price-col">Precio Unit.</th>
            <th class="subt-col">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($venta->detalles as $det)
        <tr>
            <td class="code-col">{{ $det->producto ? $det->producto->codigo : 'PROD-'.$det->id_producto }}</td>
            <td class="desc-col">
                <strong>{{ $det->producto ? $det->producto->nombre : 'Producto #' . $det->id_producto }}</strong>
                @if($det->producto && $det->producto->categoria)
                    <span style="color:#64748b; font-size: 8.5px;">({{ $det->producto->categoria->nombre }})</span>
                @endif
            </td>
            <td class="qty-col">{{ $det->cantidad }}</td>
            <td class="price-col">${{ number_format($det->precio, 2) }}</td>
            <td class="subt-col">${{ number_format($det->subtotal, 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- Totales -->
<div class="totals-table">
    <div class="totals-box">
        <div class="t-row">
            <span class="lbl">Subtotal Base (sin IVA):</span>
            <span class="val">${{ number_format($subtotalSinIva, 2) }}</span>
        </div>
        <div class="t-row">
            <span class="lbl">IVA Discriminado (19%):</span>
            <span class="val">${{ number_format($iva19, 2) }}</span>
        </div>
        <div class="t-divider"></div>
        <div class="t-final">
            <span class="lbl">TOTAL A PAGAR:</span>
            <span class="val">${{ number_format($venta->total, 2) }}</span>
        </div>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>

<!-- Pie de Página Legal -->
<table class="footer-table">
    <tr>
        <td class="footer-legal">
            <strong>Autorización DIAN:</strong> Resolución Electrónica N° 18760000001 de 2026. Prefijo FACT del 000001 al 999999. Vigencia: 24 meses.<br>
            <strong>CUFE:</strong> {{ hash('sha256', 'superfresco-' . $venta->id_venta . '-' . $venta->fecha_venta . '-' . $venta->total) }}<br>
            ¡Gracias por su compra en SuperFresco! Conserve este comprobante para cualquier garantía o cambio dentro de los 30 días calendario.
        </td>
        <td class="footer-badge">
            SUPERMERCADOS SUPERFRESCO<br>
            <span style="font-size:7.5px; font-weight:normal; color:#64748b;">Comprobante Digital Oficial</span>
        </td>
    </tr>
</table>

</body>
</html>
