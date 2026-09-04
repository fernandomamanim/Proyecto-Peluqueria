<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Panel de Administrador</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="bg-white shadow rounded-lg p-6">
                    <p class="text-sm text-gray-500">Usuarios activos</p>
                    <p class="text-2xl font-semibold">{{ $totalUsuarios }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <p class="text-sm text-gray-500">Reservas hoy</p>
                    <p class="text-2xl font-semibold">{{ $reservasHoy }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <p class="text-sm text-gray-500">Pagos pendientes</p>
                    <p class="text-2xl font-semibold">{{ $pagosPendientes }}</p>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <p class="text-sm text-gray-500">Ingresos del mes</p>
                    <p class="text-2xl font-semibold">Bs {{ number_format($ingresosMes, 2) }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>