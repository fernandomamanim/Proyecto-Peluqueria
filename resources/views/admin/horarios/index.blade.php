<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Horarios de {{ $peluquero->usuario->nombre }} {{ $peluquero->usuario->primer_apellido }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

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
                <h3 class="text-lg font-semibold mb-4">Agregar horario</h3>
                <form method="POST" action="{{ route('admin.peluqueros.horarios.store', $peluquero) }}" class="flex gap-4 items-end flex-wrap">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Día</label>
                        <select name="dia_semana" class="mt-1 block rounded border-gray-300" required>
                            @foreach (['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'] as $dia)
                                <option value="{{ $dia }}">{{ $dia }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hora inicio</label>
                        <input type="time" name="hora_inicio" class="mt-1 block rounded border-gray-300" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hora fin</label>
                        <input type="time" name="hora_fin" class="mt-1 block rounded border-gray-300" required>
                    </div>
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Agregar
                    </button>
                </form>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Horarios configurados</h3>

                @forelse ($horarios as $horario)
                    <form method="POST" action="{{ route('admin.peluqueros.horarios.update', [$peluquero, $horario]) }}"
                          class="flex gap-4 items-end flex-wrap border-b py-3">
                        @csrf
                        @method('PUT')

                        <div class="w-24 font-medium">{{ $horario->dia_semana }}</div>

                        <div>
                            <input type="time" name="hora_inicio" value="{{ substr($horario->hora_inicio, 0, 5) }}"
                                   class="block rounded border-gray-300" required>
                        </div>
                        <span>—</span>
                        <div>
                            <input type="time" name="hora_fin" value="{{ substr($horario->hora_fin, 0, 5) }}"
                                   class="block rounded border-gray-300" required>
                        </div>

                        <div>
                            <select name="estado" class="block rounded border-gray-300">
                                <option value="Disponible" @selected($horario->estado === 'Disponible')>Disponible</option>
                                <option value="NoDisponible" @selected($horario->estado === 'NoDisponible')>No disponible</option>
                            </select>
                        </div>

                        <button type="submit" class="text-blue-600 hover:underline text-sm">Guardar</button>
                    </form>

                    <form method="POST" action="{{ route('admin.peluqueros.horarios.destroy', [$peluquero, $horario]) }}"
                          onsubmit="return confirm('¿Eliminar este horario?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline text-xs mb-2">Eliminar</button>
                    </form>
                @empty
                    <p class="text-gray-500">Este peluquero aún no tiene horarios configurados.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>