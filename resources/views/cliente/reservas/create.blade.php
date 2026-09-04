<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nueva reserva</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('cliente.reservas.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Servicio</label>
                        <select name="servicio_id" class="mt-1 block w-full rounded border-gray-300" required>
                            <option value="">Selecciona un servicio</option>
                            @foreach ($servicios as $servicio)
                                <option value="{{ $servicio->id }}" @selected(old('servicio_id') == $servicio->id)>
                                    {{ $servicio->nombre }} — Bs {{ $servicio->precio }} ({{ $servicio->duracion }} min)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Peluquero</label>
                        <select name="peluquero_id" class="mt-1 block w-full rounded border-gray-300" required>
                            <option value="">Selecciona un peluquero</option>
                            @foreach ($peluqueros as $peluquero)
                                <option value="{{ $peluquero->id }}" @selected(old('peluquero_id') == $peluquero->id)>
                                    {{ $peluquero->usuario->nombre }} {{ $peluquero->usuario->primer_apellido }}
                                    — {{ $peluquero->especialidad }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fecha</label>
                        <input type="date" name="fecha" min="{{ now()->format('Y-m-d') }}"
                               value="{{ old('fecha') }}"
                               class="mt-1 block w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hora de inicio</label>
                        <input type="time" name="hora_inicio" value="{{ old('hora_inicio') }}"
                               class="mt-1 block w-full rounded border-gray-300" required>
                        <p class="text-xs text-gray-500 mt-1">La hora de fin se calcula automáticamente según la duración del servicio.</p>
                    </div>

                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Confirmar reserva
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>