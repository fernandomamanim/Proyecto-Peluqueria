<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 0; }
        p.periodo { color: #666; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte Financiero</h1>
    <p class="periodo">Periodo: {{ $desde->format('d/m/Y') }} — {{ $hasta->format('d/m/Y') }}</p>

    <table>
        <tr><th>Concepto</th><th>Monto (Bs)</th></tr>
        <tr><td>Ingresos por servicios</td><td>{{ number_format($ingresosServicios, 2) }}</td></tr>
        <tr><td>Ingresos por productos</td><td>{{ number_format($ingresosProductos, 2) }}</td></tr>
        <tr><td><strong>Total ingresos</strong></td><td><strong>{{ number_format($totalIngresos, 2) }}</strong></td></tr>
        <tr><td>Descuentos otorgados</td><td>{{ number_format($totalDescuentos, 2) }}</td></tr>
        <tr><td>Nómina mensual (egreso fijo)</td><td>{{ number_format($nominaMensual, 2) }}</td></tr>
        <tr><td><strong>Utilidad neta</strong></td><td><strong>{{ number_format($utilidadNeta, 2) }}</strong></td></tr>
        <tr><td>Cantidad de pagos procesados</td><td>{{ $cantidadPagos }}</td></tr>
    </table>
</body>
</html>