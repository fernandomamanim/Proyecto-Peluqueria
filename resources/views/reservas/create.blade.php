<x-reserva-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nueva reserva</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-2 bg-white shadow rounded-lg p-6">

                    @guest
                        <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded mb-4 text-sm">
                            ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="underline font-medium">Inicia sesión</a>
                            y obtén un <strong>8% de descuento</strong> en tu reserva.
                        </div>
                    @endguest

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('reservas.store') }}" class="space-y-4" id="form-reserva">
                        @csrf

                        @guest
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre completo</label>
                                <input type="text" name="nombre_invitado" value="{{ old('nombre_invitado') }}"
                                       class="mt-1 block w-full rounded border-gray-300" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Teléfono de contacto</label>
                                <input type="text" name="telefono_invitado" value="{{ old('telefono_invitado') }}"
                                       class="mt-1 block w-full rounded border-gray-300" required>
                            </div>
                        @endguest

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
                            <select name="peluquero_id" id="peluquero_id" class="mt-1 block w-full rounded border-gray-300" required>
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
                            <input type="date" name="fecha" id="fecha" min="{{ now()->format('Y-m-d') }}"
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

                <div class="bg-white shadow rounded-lg p-6 h-fit">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Disponibilidad</h3>
                    <div id="panel-disponibilidad" class="text-sm text-gray-500">
                        Selecciona un peluquero y una fecha para ver los horarios ocupados.
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        const selectPeluquero = document.getElementById('peluquero_id');
        const inputFecha = document.getElementById('fecha');
        const panel = document.getElementById('panel-disponibilidad');

        async function actualizarDisponibilidad() {
            const peluqueroId = selectPeluquero.value;
            const fecha = inputFecha.value;

            if (! peluqueroId || ! fecha) {
                panel.innerHTML = '<p class="text-gray-500">Selecciona un peluquero y una fecha para ver los horarios ocupados.</p>';
                return;
            }

            panel.innerHTML = '<p class="text-gray-400">Cargando...</p>';

            try {
                const url = `{{ route('reservas.disponibilidad') }}?peluquero_id=${peluqueroId}&fecha=${fecha}`;
                const respuesta = await fetch(url, { headers: { 'Accept': 'application/json' } });

                if (! respuesta.ok) {
                    panel.innerHTML = '<p class="text-red-500">No se pudo cargar la disponibilidad.</p>';
                    return;
                }

                const data = await respuesta.json();

                if (! data.atiende) {
                    panel.innerHTML = `<p class="text-red-600">El peluquero no atiende los días ${data.dia_semana}.</p>`;
                    return;
                }

                let html = `<p class="mb-2"><strong>Horario de atención:</strong><br>${data.horario_inicio} - ${data.horario_fin}</p>`;

                if (data.ocupados.length === 0) {
                    html += '<p class="text-green-600">Sin reservas aún ese día — todo el horario está libre.</p>';
                } else {
                    html += '<p class="font-medium mb-1">Horarios ya ocupados:</p><ul class="space-y-1">';
                    data.ocupados.forEach(o => {
                        html += `<li class="bg-red-50 text-red-700 px-2 py-1 rounded text-xs">${o.inicio} - ${o.fin}</li>`;
                    });
                    html += '</ul>';
                }

                panel.innerHTML = html;
            } catch (e) {
                panel.innerHTML = '<p class="text-red-500">Error al consultar disponibilidad.</p>';
            }
        }

        selectPeluquero.addEventListener('change', actualizarDisponibilidad);
        inputFecha.addEventListener('change', actualizarDisponibilidad);
    </script>
</x-reserva-layout>