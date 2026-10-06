<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reporte de Reservas / Citas</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <x-filtro-fechas :desde="$desde" :hasta="$hasta" :pdf-route="route('admin.reportes.reservas.pdf')" />

            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <p class="text-sm text-gray-500">Total de reservas en el periodo</p>
                <p class="text-2xl font-semibold">{{ $totalReservas }}</p>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="font-semibold mb-4">Por estado</h3>
                    <ul class="text-sm space-y-1">
                        @foreach ($porEstado as $estado => $cantidad)
                            <li>{{ $estado }} — {{ $cantidad }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="font-semibold mb-4">Por peluquero</h3>
                    <ul class="text-sm space-y-1">
                        @foreach ($porPeluquero as $nombre => $cantidad)
                            <li>{{ $nombre }} — {{ $cantidad }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>