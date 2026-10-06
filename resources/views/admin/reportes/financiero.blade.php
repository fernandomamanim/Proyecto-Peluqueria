<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reporte Financiero</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <x-filtro-fechas :desde="$desde" :hasta="$hasta" :pdf-route="route('admin.reportes.financiero.pdf')" />

            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                <div class="bg-white shadow rounded-lg p-4">
                    <p class="text-xs text-gray-500">Ingresos totales</p>
                    <p class="text-xl font-semibold">Bs {{ number_format($totalIngresos, 2) }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-4">
                    <p class="text-xs text-gray-500">Nómina del mes</p>
                    <p class="text-xl font-semibold">Bs {{ number_format($nominaMensual, 2) }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-4">
                    <p class="text-xs text-gray-500">Utilidad neta</p>
                    <p class="text-xl font-semibold {{ $utilidadNeta >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Bs {{ number_format($utilidadNeta, 2) }}
                    </p>
                </div>
                <div class="bg-white shadow rounded-lg p-4">
                    <p class="text-xs text-gray-500">Pagos procesados</p>
                    <p class="text-xl font-semibold">{{ $cantidadPagos }}</p>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold mb-4">Desglose de ingresos</h3>
                <dl class="grid grid-cols-2 gap-2 text-sm">
                    <dt class="text-gray-500">Por servicios</dt>
                    <dd>Bs {{ number_format($ingresosServicios, 2) }}</dd>
                    <dt class="text-gray-500">Por productos</dt>
                    <dd>Bs {{ number_format($ingresosProductos, 2) }}</dd>
                    <dt class="text-gray-500">Descuentos otorgados</dt>
                    <dd>Bs {{ number_format($totalDescuentos, 2) }}</dd>
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>