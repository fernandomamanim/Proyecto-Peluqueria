<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mi cuenta — {{ auth()->user()->nombre }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Mis reservas</h3>
                    <a href="{{ route('cliente.reservas.create') }}"
                       class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Nueva reserva
                    </a>
                </div>

                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Fecha</th>
                            <th class="py-2">Servicio</th>
                            <th class="py-2">Peluquero</th>
                            <th class="py-2">Estado</th>
                            <th class="py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservas as $reserva)
                            <tr class="border-b">
                                <td class="py-2">{{ $reserva->fecha->format('d/m/Y') }} {{ $reserva->hora_inicio }}</td>
                                <td class="py-2">{{ $reserva->servicio->nombre }}</td>
                                <td class="py-2">{{ $reserva->peluquero->usuario->nombre }}</td>
                                <td class="py-2">
                                    <span class="px-2 py-1 rounded text-xs
                                        @class([
                                            'bg-yellow-100 text-yellow-800' => $reserva->estado === 'Pendiente',
                                            'bg-green-100 text-green-800' => $reserva->estado === 'Confirmada',
                                            'bg-red-100 text-red-800' => in_array($reserva->estado, ['Cancelada', 'Expirada']),
                                            'bg-gray-100 text-gray-800' => $reserva->estado === 'Finalizada',
                                        ])">
                                        {{ $reserva->estado }}
                                    </span>
                                </td>
                                <td class="py-2">
                                    <a href="{{ route('cliente.reservas.show', $reserva) }}" class="text-blue-600 hover:underline">
                                        Ver
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-4 text-gray-500 text-center">Aún no tienes reservas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>