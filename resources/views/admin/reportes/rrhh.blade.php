<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reporte de Recursos Humanos</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <x-filtro-fechas :desde="$desde" :hasta="$hasta" :pdf-route="route('admin.reportes.rrhh.pdf')" />

            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <p class="text-sm text-gray-500">Nómina total (salarios activos)</p>
                <p class="text-2xl font-semibold">Bs {{ number_format($totalNomina, 2) }}</p>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold mb-4">Asistencia y nómina por peluquero</h3>
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Peluquero</th>
                            <th class="py-2">Días asistidos</th>
                            <th class="py-2">Salario mensual</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($filas as $fila)
                            <tr class="border-b">
                                <td class="py-2">{{ $fila['peluquero']->usuario->nombre }} {{ $fila['peluquero']->usuario->primer_apellido }}</td>
                                <td class="py-2">{{ $fila['dias_asistidos'] }}</td>
                                <td class="py-2">Bs {{ number_format($fila['salario_mensual'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>