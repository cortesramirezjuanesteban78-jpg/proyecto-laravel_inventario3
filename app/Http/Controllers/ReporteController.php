<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    /**
     * Compila los datos estadísticos y métricas del año seleccionado.
     */
    private function getReportData($year)
    {
        // ── Stat cards ──────────────────────────────────────────
        $totalUsuarios  = Usuario::count();
        $totalProductos = Producto::count();
        $totalVentas    = (float) DB::table('ventas')
            ->whereYear('fecha_venta', $year)
            ->sum('total');
        $totalCompras   = (float) (DB::table('compras')
            ->whereYear('fecha_compra', $year)
            ->sum('total') ?? 0);

        // ── Ventas por mes ──────────────────────────────────────
        $ventasPorMes = DB::table('ventas')
            ->selectRaw('MONTH(fecha_venta) as mes, SUM(total) as total, COUNT(*) as cantidad')
            ->whereYear('fecha_venta', $year)
            ->groupByRaw('MONTH(fecha_venta)')
            ->orderBy('mes')
            ->get()
            ->keyBy('mes');

        // Llenar los 12 meses
        $meses = [];
        $mesesNombres = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];
        $maxVenta = 0;
        for ($m = 1; $m <= 12; $m++) {
            $val = $ventasPorMes->get($m);
            $total = $val ? (float)$val->total : 0;
            if ($total > $maxVenta) $maxVenta = $total;
            $meses[$m] = [
                'nombre'   => $mesesNombres[$m - 1],
                'total'    => $total,
                'cantidad' => $val ? (int)$val->cantidad : 0,
            ];
        }

        // ── Top 10 productos más vendidos ───────────────────────
        $topProductos = DB::table('detalle_ventas')
            ->join('productos', 'detalle_ventas.id_producto', '=', 'productos.id_producto')
            ->join('ventas', 'detalle_ventas.id_venta', '=', 'ventas.id_venta')
            ->whereYear('ventas.fecha_venta', $year)
            ->selectRaw('productos.codigo, productos.nombre, SUM(detalle_ventas.cantidad) as total_vendido, SUM(detalle_ventas.subtotal) as ingresos')
            ->groupBy('detalle_ventas.id_producto', 'productos.codigo', 'productos.nombre')
            ->orderByDesc('total_vendido')
            ->limit(10)
            ->get();

        // ── Productos con stock bajo o agotado ──────────────────
        $productosStockBajo = Producto::with('categoria')
            ->where('estado', 1)
            ->whereColumn('stock_actual', '<=', 'stock_minimo')
            ->orderBy('stock_actual')
            ->get();

        // ── Compras por proveedor ───────────────────────────────
        $comprasPorProveedor = DB::table('compras')
            ->join('proveedores', 'compras.id_proveedor', '=', 'proveedores.id_proveedor')
            ->whereYear('fecha_compra', $year)
            ->selectRaw('proveedores.nombre, COUNT(*) as total_compras, SUM(compras.total) as total_gastado')
            ->groupBy('compras.id_proveedor', 'proveedores.nombre')
            ->orderByDesc('total_gastado')
            ->get();

        return compact(
            'year', 'totalUsuarios', 'totalProductos', 'totalVentas', 'totalCompras',
            'meses', 'maxVenta', 'topProductos', 'productosStockBajo', 'comprasPorProveedor'
        );
    }

    /**
     * Muestra la vista analítica interactiva de reportes en el panel.
     */
    public function index(Request $r)
    {
        $year = (int) $r->get('year', now()->year);
        $data = $this->getReportData($year);

        return view('reportes.index', $data);
    }

    /**
     * Genera y descarga el reporte en formato PDF (A4 vertical).
     */
    public function exportPdf(Request $r)
    {
        $year = (int) $r->get('year', now()->year);
        $data = $this->getReportData($year);

        $pdf = Pdf::loadView('reportes.pdf', $data)
                  ->setPaper('a4', 'portrait')
                  ->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->download("reporte_superfresco_{$year}.pdf");
    }

    /**
     * Genera y descarga el reporte en formato tabular Excel (.csv / .xls con UTF-8 BOM).
     */
    public function exportExcel(Request $r)
    {
        $year = (int) $r->get('year', now()->year);
        $data = $this->getReportData($year);

        $filename = "reporte_superfresco_{$year}.csv";

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($data, $year) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM para que Excel reconozca tildes y caracteres en español
            fputs($handle, "\xEF\xBB\xBF");
            // Indicador de separador para Excel
            fputs($handle, "sep=;\n");

            // ── Encabezado ──────────────────────────────────────────
            fputcsv($handle, ['SUPERFRESCO - REPORTE EJECUTIVO ANUAL'], ';');
            fputcsv($handle, ['Año del Informe', $year], ';');
            fputcsv($handle, ['Fecha de Emisión', now()->format('d/m/Y H:i:s')], ';');
            fputcsv($handle, ['Generado por', auth()->user()->nombres ?? 'Administrador'], ';');
            fputcsv($handle, [], ';');

            // ── Resumen General (KPIs) ──────────────────────────────
            fputcsv($handle, ['--- RESUMEN GENERAL (KPIS) ---'], ';');
            fputcsv($handle, ['Métrica', 'Valor'], ';');
            fputcsv($handle, ['Ventas Totales del Año', '$' . number_format($data['totalVentas'], 2)], ';');
            fputcsv($handle, ['Compras a Proveedores', '$' . number_format($data['totalCompras'], 2)], ';');
            fputcsv($handle, ['Total Productos en Catálogo', $data['totalProductos']], ';');
            fputcsv($handle, ['Total Usuarios Registrados', $data['totalUsuarios']], ';');
            fputcsv($handle, ['Productos con Alerta de Stock', $data['productosStockBajo']->count()], ';');
            fputcsv($handle, [], ';');

            // ── 1. Ventas por Mes ───────────────────────────────────
            fputcsv($handle, ['--- 1. VENTAS MENSUALES ---'], ';');
            fputcsv($handle, ['Mes', 'N° Transacciones', 'Total Facturado ($)', 'Participación (%)'], ';');
            $totQty = 0;
            $totVentas = 0;
            foreach ($data['meses'] as $m) {
                $pct = $data['totalVentas'] > 0 ? ($m['total'] / $data['totalVentas']) * 100 : 0;
                $totQty += $m['cantidad'];
                $totVentas += $m['total'];
                fputcsv($handle, [
                    $m['nombre'],
                    $m['cantidad'],
                    number_format($m['total'], 2),
                    number_format($pct, 1) . '%'
                ], ';');
            }
            fputcsv($handle, ['TOTAL ANUAL', $totQty, number_format($totVentas, 2), '100.0%'], ';');
            fputcsv($handle, [], ';');

            // ── 2. Top 10 Productos Más Vendidos ───────────────────
            fputcsv($handle, ['--- 2. TOP 10 PRODUCTOS MÁS VENDIDOS ---'], ';');
            fputcsv($handle, ['# Ranking', 'Código', 'Producto', 'Unidades Vendidas', 'Ingresos Generados ($)'], ';');
            foreach ($data['topProductos'] as $idx => $p) {
                fputcsv($handle, [
                    $idx + 1,
                    $p->codigo ?? 'N/A',
                    $p->nombre,
                    $p->total_vendido,
                    number_format($p->ingresos, 2)
                ], ';');
            }
            fputcsv($handle, [], ';');

            // ── 3. Inventario Crítico ──────────────────────────────
            fputcsv($handle, ['--- 3. ESTADO CRÍTICO DE INVENTARIO ---'], ';');
            fputcsv($handle, ['Código', 'Producto', 'Categoría', 'Stock Actual', 'Stock Mínimo', 'Precio ($)', 'Estado'], ';');
            foreach ($data['productosStockBajo'] as $prod) {
                fputcsv($handle, [
                    $prod->codigo,
                    $prod->nombre,
                    $prod->categoria->nombre ?? 'Sin categoría',
                    $prod->stock_actual,
                    $prod->stock_minimo,
                    number_format($prod->precio, 2),
                    $prod->stock_actual == 0 ? 'AGOTADO' : 'STOCK BAJO'
                ], ';');
            }
            fputcsv($handle, [], ';');

            // ── 4. Compras por Proveedor ────────────────────────────
            fputcsv($handle, ['--- 4. COMPRAS POR PROVEEDOR ---'], ';');
            fputcsv($handle, ['Proveedor', 'N° Órdenes / Facturas', 'Total Facturado ($)'], ';');
            foreach ($data['comprasPorProveedor'] as $comp) {
                fputcsv($handle, [
                    $comp->nombre,
                    $comp->total_compras,
                    number_format($comp->total_gastado, 2)
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
