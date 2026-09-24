<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Ejecutivo {{ $year }} — SuperFresco</title>
    <style>
        @page {
            margin: 18mm 14mm 20mm 14mm;
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
            margin-bottom: 18px;
        }
        .brand-title {
            font-size: 20px;
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
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2px;
        }
        .report-badge {
            text-align: right;
        }
        .report-badge h2 {
            margin: 0;
            font-size: 14px;
            color: #0f172a;
            text-transform: uppercase;
        }
        .report-badge p {
            margin: 2px 0 0 0;
            font-size: 9px;
            color: #64748b;
        }

        /* Tarjetas de Resumen (KPIs) */
        .kpi-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: separate;
            border-spacing: 8px 0;
        }
        .kpi-cell {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid #106f4e;
            padding: 10px 12px;
            border-radius: 6px;
            vertical-align: top;
            width: 25%;
        }
        .kpi-cell.blue { border-left-color: #2563eb; }
        .kpi-cell.orange { border-left-color: #d97706; }
        .kpi-cell.red { border-left-color: #f43f5e; }
        .kpi-label {
            font-size: 9px;
            text-transform: uppercase;
            color: #64748b;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .kpi-val {
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
        }

        /* Secciones y Tablas */
        .section-title {
            font-size: 12px;
            font-weight: bold;
            color: #0b4e37;
            text-transform: uppercase;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
            margin-top: 16px;
            margin-bottom: 10px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 10px;
        }
        table.data-table th {
            background: #0f172a;
            color: #ffffff;
            text-align: left;
            padding: 6px 8px;
            font-weight: 600;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        table.data-table td {
            padding: 5px 8px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        table.data-table tr:nth-child(even) td {
            background-color: #f8fafc;
        }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .font-bold { font-weight: bold; }
        .text-green { color: #106f4e; font-weight: bold; }
        .text-red { color: #e11d48; font-weight: bold; }

        /* Badges de estado */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8px;
            font-weight: bold;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .badge-danger { background: #ffe4e6; color: #9f1239; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-cat { background: #e0f2fe; color: #0369a1; }

        /* Pie de página */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 6px;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Encabezado Institucional -->
    <table class="header-table">
        <tr>
            <td style="vertical-align: middle;">
                <div class="brand-title">Super<span>Fresco</span></div>
                <div class="brand-sub">Mercado Gourmet & Orgánico — Sistema de Gestión</div>
            </td>
            <td class="report-badge" style="vertical-align: middle;">
                <h2>Informe Anual {{ $year }}</h2>
                <p>Fecha de emisión: {{ now()->format('d/m/Y H:i') }}</p>
                <p>Generado por: {{ auth()->user()->nombres ?? 'Administrador' }} ({{ auth()->user()->rol ?? 'Admin' }})</p>
            </td>
        </tr>
    </table>

    <!-- Tarjetas Resumen (KPIs) -->
    <table class="kpi-table">
        <tr>
            <td class="kpi-cell">
                <div class="kpi-label">Ventas Totales ({{ $year }})</div>
                <div class="kpi-val text-green">${{ number_format($totalVentas, 2) }}</div>
            </td>
            <td class="kpi-cell blue">
                <div class="kpi-label">Compras a Proveedores</div>
                <div class="kpi-val">${{ number_format($totalCompras, 2) }}</div>
            </td>
            <td class="kpi-cell orange">
                <div class="kpi-label">Catálogo de Productos</div>
                <div class="kpi-val">{{ $totalProductos }} ítems</div>
            </td>
            <td class="kpi-cell red">
                <div class="kpi-label">Alertas de Stock</div>
                <div class="kpi-val text-red">{{ $productosStockBajo->count() }} prod.</div>
            </td>
        </tr>
    </table>

    <!-- 1. Desglose Mensual de Ventas -->
    <div class="section-title">1. Rendimiento Mensual de Ventas ({{ $year }})</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 25%;">Mes</th>
                <th class="text-center" style="width: 25%;">N° Transacciones</th>
                <th class="text-right" style="width: 25%;">Total Facturado</th>
                <th class="text-right" style="width: 25%;">Participación</th>
            </tr>
        </thead>
        <tbody>
            @php $acumulado = 0; @endphp
            @foreach($meses as $m)
            @php
                $pct = $totalVentas > 0 ? ($m['total'] / $totalVentas) * 100 : 0;
                $acumulado += $m['total'];
            @endphp
            <tr>
                <td class="font-bold">{{ $m['nombre'] }}</td>
                <td class="text-center">{{ $m['cantidad'] }}</td>
                <td class="text-right">${{ number_format($m['total'], 2) }}</td>
                <td class="text-right">{{ number_format($pct, 1) }}%</td>
            </tr>
            @endforeach
            <tr style="background: #e2e8f0; font-weight: bold;">
                <td>TOTAL ANUAL</td>
                <td class="text-center">{{ collect($meses)->sum('cantidad') }}</td>
                <td class="text-right text-green">${{ number_format($acumulado, 2) }}</td>
                <td class="text-right">100.0%</td>
            </tr>
        </tbody>
    </table>

    <!-- 2. Top 10 Productos Más Vendidos -->
    <div class="section-title">2. Top 10 Productos Más Vendidos</div>
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 10%;">#</th>
                <th style="width: 50%;">Producto</th>
                <th class="text-center" style="width: 20%;">Unidades Vendidas</th>
                <th class="text-right" style="width: 20%;">Ingresos Generados</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topProductos as $idx => $p)
            <tr>
                <td class="text-center font-bold">{{ $idx + 1 }}</td>
                <td>{{ $p->nombre }}</td>
                <td class="text-center font-bold">{{ number_format($p->total_vendido) }} u.</td>
                <td class="text-right text-green">${{ number_format($p->ingresos, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center" style="color:#64748b; padding:12px;">No se registraron ventas de productos en este período.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- 3. Alertas de Inventario Crítico -->
    <div class="section-title">3. Estado Crítico de Inventario (Stock Bajo o Agotado)</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 40%;">Producto</th>
                <th style="width: 25%;">Categoría</th>
                <th class="text-center" style="width: 15%;">Stock Actual</th>
                <th class="text-center" style="width: 10%;">Stock Mínimo</th>
                <th class="text-center" style="width: 10%;">Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($productosStockBajo as $prod)
            <tr>
                <td class="font-bold">{{ $prod->nombre }}</td>
                <td><span class="badge badge-cat">{{ $prod->categoria->nombre ?? 'Sin categoría' }}</span></td>
                <td class="text-center font-bold text-red">{{ $prod->stock_actual }}</td>
                <td class="text-center">{{ $prod->stock_minimo }}</td>
                <td class="text-center">
                    @if($prod->stock_actual == 0)
                        <span class="badge badge-danger">Agotado</span>
                    @else
                        <span class="badge badge-warning">Bajo</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="color:#106f4e; padding:10px;">✓ Todo el inventario se encuentra en niveles óptimos.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- 4. Compras por Proveedor -->
    <div class="section-title">4. Compras Realizadas por Proveedor</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 50%;">Proveedor</th>
                <th class="text-center" style="width: 25%;">Órdenes / Facturas</th>
                <th class="text-right" style="width: 25%;">Total Facturado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($comprasPorProveedor as $comp)
            <tr>
                <td class="font-bold">{{ $comp->nombre }}</td>
                <td class="text-center">{{ $comp->total_compras }}</td>
                <td class="text-right">${{ number_format($comp->total_gastado, 2) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center" style="color:#64748b; padding:10px;">No se registraron compras en este período.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pie de página -->
    <div class="footer">
        SuperFresco &copy; {{ date('Y') }} — Documento confidencial para uso administrativo interno. Generado automáticamente.
    </div>

</body>
</html>
