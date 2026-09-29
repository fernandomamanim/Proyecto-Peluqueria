<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis reservas</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Fecha</th>
                            <th class="py-2">Cliente</th>
                            <th class="py-2">Servicio</th>
                            <th class="py-2">Estado</th>
                            <th class="py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservas as $reserva)
                            <tr class="border-b">
                                <td class="py-2">{{ $reserva->fecha->format('d/m/Y') }} {{ substr($reserva->hora_inicio, 0, 5) }}</td>
                                <td class="py-2">{{ $reserva->nombre_cliente }}</td>
                                <td class="py-2">{{ $reserva->servicio->nombre }}</td>
                                <td class="py-2">{{ $reserva->estado }}</td>
                                <td class="py-2">
                                    <a href="{{ route('staff.reservas.show', $reserva) }}" class="text-blue-600 hover:underline">
                                        Ver / agregar productos
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-gray-500 text-center">No tienes reservas activas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>