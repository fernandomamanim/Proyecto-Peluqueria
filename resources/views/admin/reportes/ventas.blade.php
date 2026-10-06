<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reporte de Ventas</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <x-filtro-fechas :desde="$desde" :hasta="$hasta" :pdf-route="route('admin.reportes.ventas.pdf')" />

            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <p class="text-sm text-gray-500">Total vendido en el periodo</p>
                <p class="text-2xl font-semibold">Bs {{ number_format($totalVentas, 2) }}</p>
            </div>

            <div class="grid grid-cols-2 gap-6 mb-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="font-semibold mb-4">Servicios más vendidos</h3>
                    <ul class="text-sm space-y-1">
                        @foreach ($porServicio as $nombre => $cantidad)
                            <li>{{ $nombre }} — {{ $cantidad }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="font-semibold mb-4">Productos más vendidos</h3>
                    <ul class="text-sm space-y-1">
                        @forelse ($porProducto as $nombre => $cantidad)
                            <li>{{ $nombre }} — {{ $cantidad }} unidades</li>
                        @empty
                            <li class="text-gray-500">Sin ventas de productos en este periodo.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold mb-4">Detalle de ventas</h3>
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Fecha</th>
                            <th class="py-2">Cliente</th>
                            <th class="py-2">Servicio</th>
                            <th class="py-2">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pagos as $pago)
                            <tr class="border-b">
                                <td class="py-2">{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                                <td class="py-2">{{ $pago->reserva->nombre_cliente }}</td>
                                <td class="py-2">{{ $pago->reserva->servicio->nombre }}</td>
                                <td class="py-2">Bs {{ $pago->monto }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>