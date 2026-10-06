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
    <h1>Reporte de Recursos Humanos</h1>
    <p class="periodo">Periodo de asistencia: {{ $desde->format('d/m/Y') }} — {{ $hasta->format('d/m/Y') }}</p>
    <p><strong>Nómina total:</strong> Bs {{ number_format($totalNomina, 2) }}</p>

    <table>
        <tr><th>Peluquero</th><th>Días asistidos</th><th>Salario mensual (Bs)</th></tr>
        @foreach ($filas as $fila)
            <tr>
                <td>{{ $fila['peluquero']->usuario->nombre }} {{ $fila['peluquero']->usuario->primer_apellido }}</td>
                <td>{{ $fila['dias_asistidos'] }}</td>
                <td>{{ number_format($fila['salario_mensual'], 2) }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>