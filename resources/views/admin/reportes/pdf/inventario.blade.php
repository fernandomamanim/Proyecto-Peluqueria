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
    <h1>Reporte de Inventario</h1>
    <p class="periodo">Periodo de movimientos: {{ $desde->format('d/m/Y') }} — {{ $hasta->format('d/m/Y') }}</p>
    <p><strong>Valor total del inventario:</strong> Bs {{ number_format($valorInventario, 2) }}</p>

    <h2>Stock actual</h2>
    <table>
        <tr><th>Producto</th><th>Precio</th><th>Stock</th><th>Valor</th></tr>
        @foreach ($productos as $producto)
            <tr>
                <td>{{ $producto->nombre }}</td>
                <td>{{ number_format($producto->precio, 2) }}</td>
                <td>{{ $producto->stock }}</td>
                <td>{{ number_format($producto->precio * $producto->stock, 2) }}</td>
            </tr>
        @endforeach
    </table>

    <h2>Movimientos del periodo</h2>
    <table>
        <tr><th>Fecha</th><th>Producto</th><th>Tipo</th><th>Cantidad</th></tr>
        @foreach ($movimientos as $mov)
            <tr>
                <td>{{ $mov->fecha_movimiento->format('d/m/Y H:i') }}</td>
                <td>{{ $mov->producto->nombre }}</td>
                <td>{{ $mov->tipo_movimiento }}</td>
                <td>{{ $mov->cantidad }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>