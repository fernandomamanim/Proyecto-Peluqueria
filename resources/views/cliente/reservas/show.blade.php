<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalle de reserva</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Reserva #{{ $reserva->id }}</h3>
                <dl class="grid grid-cols-2 gap-2 text-sm">
                    <dt class="text-gray-500">Servicio</dt>
                    <dd>{{ $reserva->servicio->nombre }}</dd>

                    <dt class="text-gray-500">Peluquero</dt>
                    <dd>{{ $reserva->peluquero->usuario->nombre }}</dd>

                    <dt class="text-gray-500">Fecha</dt>
                    <dd>{{ $reserva->fecha->format('d/m/Y') }}</dd>

                    <dt class="text-gray-500">Horario</dt>
                    <dd>{{ $reserva->hora_inicio }} — {{ $reserva->hora_fin }}</dd>

                    <dt class="text-gray-500">Estado</dt>
                    <dd class="font-semibold">{{ $reserva->estado }}</dd>

                    <dt class="text-gray-500">Código QR</dt>
                    <dd>{{ $reserva->codigo_qr }}</dd>
                </dl>
            </div>

            @if (! $reserva->pago)
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Registrar pago</h3>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('cliente.pagos.store', $reserva) }}"
                          enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Monto (Bs)</label>
                            <input type="number" step="0.01" name="monto"
                                   value="{{ $reserva->servicio->precio }}"
                                   class="mt-1 block w-full rounded border-gray-300" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Método de pago</label>
                            <select name="metodo_pago" class="mt-1 block w-full rounded border-gray-300" required>
                                <option value="QR">QR</option>
                                <option value="Efectivo">Efectivo</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Comprobante (imagen)</label>
                            <input type="file" name="comprobante" accept="image/*"
                                   class="mt-1 block w-full">
                        </div>

                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
                            Enviar comprobante
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">Pago</h3>
                    <p>Estado: <span class="font-semibold">{{ $reserva->pago->estado }}</span></p>
                    @if ($reserva->pago->comprobante)
                        <img src="{{ Storage::url($reserva->pago->comprobante) }}"
                             class="mt-2 max-w-xs rounded border" alt="Comprobante">
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-app-layout>