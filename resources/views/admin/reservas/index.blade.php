<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Todas las reservas</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                <form method="GET" class="flex gap-4 items-end mb-4 flex-wrap">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="estado" class="mt-1 block rounded border-gray-300" onchange="this.form.submit()">
                            <option value="">Todos</option>
                            @foreach (['Pendiente','Confirmada','Cancelada','Finalizada','Expirada'] as $estado)
                                <option value="{{ $estado }}" @selected(request('estado') === $estado)>{{ $estado }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Peluquero</label>
                        <select name="peluquero_id" class="mt-1 block rounded border-gray-300" onchange="this.form.submit()">
                            <option value="">Todos</option>
                            @foreach ($peluqueros as $peluquero)
                                <option value="{{ $peluquero->id }}" @selected((string) request('peluquero_id') === (string) $peluquero->id)>
                                    {{ $peluquero->usuario->nombre }} {{ $peluquero->usuario->primer_apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @if (request('estado') || request('peluquero_id'))
                        <a href="{{ route('admin.reservas.index') }}" class="text-sm text-gray-500 hover:underline">Limpiar filtros</a>
                    @endif
                </form>

                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Cliente</th>
                            <th class="py-2">Peluquero</th>
                            <th class="py-2">Servicio</th>
                            <th class="py-2">Fecha</th>
                            <th class="py-2">Horario</th>
                            <th class="py-2">Estado reserva</th>
                            <th class="py-2">Estado pago</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservas as $reserva)
                            <tr class="border-b">
                                <td class="py-2">
                                    {{ $reserva->nombre_cliente }}
                                    @unless ($reserva->usuario_id)
                                        <span class="text-xs text-gray-400">(invitado)</span>
                                    @endunless
                                </td>
                                <td class="py-2">{{ $reserva->peluquero->usuario->nombre }}</td>
                                <td class="py-2">{{ $reserva->servicio->nombre }}</td>
                                <td class="py-2">{{ $reserva->fecha->format('d/m/Y') }}</td>
                                <td class="py-2">{{ substr($reserva->hora_inicio, 0, 5) }} - {{ substr($reserva->hora_fin, 0, 5) }}</td>
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
                                <td class="py-2">{{ $reserva->pago->estado ?? 'Sin pago' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-gray-500 text-center">No hay reservas con esos filtros.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>