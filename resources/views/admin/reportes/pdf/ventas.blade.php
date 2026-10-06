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
    <h1>Reporte de Ventas</h1>
    <p class="periodo">Periodo: {{ $desde->format('d/m/Y') }} — {{ $hasta->format('d/m/Y') }}</p>
    <p><strong>Total vendido:</strong> Bs {{ number_format($totalVentas, 2) }}</p>

    <table>
        <tr><th>Fecha</th><th>Cliente</th><th>Servicio</th><th>Monto (Bs)</th></tr>
        @foreach ($pagos as $pago)
            <tr>
                <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                <td>{{ $pago->reserva->nombre_cliente }}</td>
                <td>{{ $pago->reserva->servicio->nombre }}</td>
                <td>{{ number_format($pago->monto, 2) }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>