<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reportes</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('admin.reportes.financiero') }}" class="bg-white shadow rounded-lg p-6 hover:shadow-md">
                    <h3 class="font-semibold text-lg mb-1">Financiero</h3>
                    <p class="text-sm text-gray-500">Ingresos, descuentos y utilidad neta.</p>
                </a>
                <a href="{{ route('admin.reportes.ventas') }}" class="bg-white shadow rounded-lg p-6 hover:shadow-md">
                    <h3 class="font-semibold text-lg mb-1">Ventas</h3>
                    <p class="text-sm text-gray-500">Servicios y productos más vendidos.</p>
                </a>
                <a href="{{ route('admin.reportes.inventario') }}" class="bg-white shadow rounded-lg p-6 hover:shadow-md">
                    <h3 class="font-semibold text-lg mb-1">Inventario</h3>
                    <p class="text-sm text-gray-500">Stock, valor de inventario y movimientos.</p>
                </a>
                <a href="{{ route('admin.reportes.rrhh') }}" class="bg-white shadow rounded-lg p-6 hover:shadow-md">
                    <h3 class="font-semibold text-lg mb-1">Recursos Humanos</h3>
                    <p class="text-sm text-gray-500">Asistencia y nómina de peluqueros.</p>
                </a>
                <a href="{{ route('admin.reportes.reservas') }}" class="bg-white shadow rounded-lg p-6 hover:shadow-md">
                    <h3 class="font-semibold text-lg mb-1">Reservas / Citas</h3>
                    <p class="text-sm text-gray-500">Volumen de reservas por estado y peluquero.</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>