<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mi asistencia</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Hoy — {{ now()->format('d/m/Y') }}</h3>

                <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                    <div>
                        <p class="text-gray-500">Entrada</p>
                        <p class="font-semibold">{{ $asistenciaHoy->hora_entrada ? substr($asistenciaHoy->hora_entrada, 0, 5) : 'No marcada' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Salida</p>
                        <p class="font-semibold">{{ $asistenciaHoy->hora_salida ? substr($asistenciaHoy->hora_salida, 0, 5) : 'No marcada' }}</p>
                    </div>
                </div>

                <div class="space-x-2">
                    <form method="POST" action="{{ route('staff.asistencia.entrada') }}" class="inline">
                        @csrf
                        <button type="submit" @disabled($asistenciaHoy->hora_entrada)
                                class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 disabled:bg-gray-300">
                            Marcar entrada
                        </button>
                    </form>
                    <form method="POST" action="{{ route('staff.asistencia.salida') }}" class="inline">
                        @csrf
                        <button type="submit" @disabled(! $asistenciaHoy->hora_entrada || $asistenciaHoy->hora_salida)
                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 disabled:bg-gray-300">
                            Marcar salida
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Historial reciente</h3>
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Fecha</th>
                            <th class="py-2">Entrada</th>
                            <th class="py-2">Salida</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($historial as $registro)
                            <tr class="border-b">
                                <td class="py-2">{{ $registro->fecha->format('d/m/Y') }}</td>
                                <td class="py-2">{{ $registro->hora_entrada ? substr($registro->hora_entrada, 0, 5) : '—' }}</td>
                                <td class="py-2">{{ $registro->hora_salida ? substr($registro->hora_salida, 0, 5) : '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-4 text-gray-500 text-center">Sin registros aún.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>