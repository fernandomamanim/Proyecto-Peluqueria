<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 18px; margin-bottom: 0; }
        h2 { font-size: 14px; margin-top: 20px; }
        p.periodo { color: #666; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Reporte de Reservas / Citas</h1>
    <p class="periodo">Periodo: {{ $desde->format('d/m/Y') }} — {{ $hasta->format('d/m/Y') }}</p>
    <p><strong>Total de reservas:</strong> {{ $totalReservas }}</p>

    <h2>Por estado</h2>
    <table>
        <tr><th>Estado</th><th>Cantidad</th></tr>
        @foreach ($porEstado as $estado => $cantidad)
            <tr><td>{{ $estado }}</td><td>{{ $cantidad }}</td></tr>
        @endforeach
    </table>

    <h2>Por peluquero</h2>
    <table>
        <tr><th>Peluquero</th><th>Cantidad</th></tr>
        @foreach ($porPeluquero as $nombre => $cantidad)
            <tr><td>{{ $nombre }}</td><td>{{ $cantidad }}</td></tr>
        @endforeach
    </table>
</body>
</html>