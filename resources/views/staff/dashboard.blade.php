<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Panel de Staff</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Pagos pendientes de aprobación</h3>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Cliente</th>
                            <th class="py-2">Servicio</th>
                            <th class="py-2">Fecha</th>
                            <th class="py-2">Horario</th>
                            <th class="py-2">Monto</th>
                            <th class="py-2">Comprobante</th>
                            <th class="py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pagosPendientes as $pago)
                            <tr class="border-b">
                                <td class="py-2">{{ $pago->reserva->cliente->nombre }}</td>
                                <td class="py-2">{{ $pago->reserva->servicio->nombre }}</td>
                                <td class="py-2">{{ $pago->reserva->fecha->format('d/m/Y') }}</td>
                                <td class="py-2">{{ substr($pago->reserva->hora_inicio, 0, 5) }} - {{ substr($pago->reserva->hora_fin, 0, 5) }}</td>
                                <td class="py-2">Bs {{ $pago->monto }}</td>
                                <td class="py-2">
                                    @if ($pago->comprobante)
                                        <a href="{{ Storage::url($pago->comprobante) }}" target="_blank" class="text-blue-600 hover:underline">
                                            Ver imagen
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="py-2 space-x-2">
                                    <form method="POST" action="{{ route('staff.pagos.aprobar', $pago) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                            Aprobar
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('staff.pagos.rechazar', $pago) }}" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded text-sm hover:bg-red-700">
                                            Rechazar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 text-gray-500 text-center">No hay pagos pendientes.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>