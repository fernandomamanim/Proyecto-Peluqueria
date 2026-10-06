<x-reserva-layout>
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
                    <dt class="text-gray-500">Cliente</dt>
                    <dd>{{ $reserva->nombre_cliente }}</dd>

                    <dt class="text-gray-500">Servicio</dt>
                    <dd>{{ $reserva->servicio->nombre }}</dd>

                    <dt class="text-gray-500">Peluquero</dt>
                    <dd>{{ $reserva->peluquero->usuario->nombre }}</dd>

                    <dt class="text-gray-500">Fecha</dt>
                    <dd>{{ $reserva->fecha->format('d/m/Y') }}</dd>

                    <dt class="text-gray-500">Horario</dt>
                    <dd>{{ substr($reserva->hora_inicio, 0, 5) }} — {{ substr($reserva->hora_fin, 0, 5) }}</dd>

                    <dt class="text-gray-500">Estado</dt>
                    <dd class="font-semibold">{{ $reserva->estado }}</dd>

                    <dt class="text-gray-500">Código QR</dt>
                    <dd>{{ $reserva->codigo_qr }}</dd>

                    <dt class="text-gray-500 font-semibold">PAGUE AQUI</dt>
                    <di><img src="{{ asset('images/qrejm.png') }}" alt="La Guarida Barber Studio" class="logo"><di>
                </dl>
                

                @if (! $reserva->usuario_id)
                    <p class="text-xs text-gray-500 mt-4">
                        Guarda este enlace — al no tener cuenta, es la única forma de volver a consultar tu reserva.
                    </p>
                @endif
            </div>

            @if (! $reserva->pago)
            @if ($reserva->reservaProductos->isNotEmpty())
                        <div class="text-sm mb-2">
                            <p class="font-medium">Productos:</p>
                            <ul class="list-disc list-inside text-gray-600">
                                @foreach ($reserva->reservaProductos as $rp)
                                    <li>{{ $rp->producto->nombre }} x{{ $rp->cantidad }} — Bs {{ $rp->subtotal }}</li>
                                @endforeach
                            </ul>
                        </div>
            @endif
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Agregar productos (opcional)</h3>
                <p class="text-xs text-gray-500 mb-4">Puedes agregar productos como shampoo o cera que quieras adquirir junto con tu servicio.</p>

                <form method="POST" action="{{ route('reservas.productos.store', $reserva) }}" class="flex gap-4 items-end mb-4 flex-wrap">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Producto</label>
                        <select name="producto_id" class="mt-1 block w-full rounded border-gray-300" required>
                            <option value="">Selecciona un producto</option>
                            @foreach ($productos as $producto)
                                <option value="{{ $producto->id }}">
                                    {{ $producto->nombre }} — Bs {{ $producto->precio }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cantidad</label>
                        <input type="number" name="cantidad" min="1" value="1" class="mt-1 block w-20 rounded border-gray-300" required>
                    </div>
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Agregar
                    </button>
                </form>

                @if ($reserva->reservaProductos->isNotEmpty())
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="py-2">Producto</th>
                                <th class="py-2">Cantidad</th>
                                <th class="py-2">Subtotal</th>
                                <th class="py-2"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reserva->reservaProductos as $rp)
                                <tr class="border-b">
                                    <td class="py-2">{{ $rp->producto->nombre }}</td>
                                    <td class="py-2">{{ $rp->cantidad }}</td>
                                    <td class="py-2">Bs {{ $rp->subtotal }}</td>
                                    <td class="py-2">
                                        <form method="POST" action="{{ route('reservas.productos.destroy', [$reserva, $rp]) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline text-xs">Quitar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
                @php
                    $descuento = $reserva->usuario_id ? 8 : 0;
                    $totalBruto = $reserva->servicio->precio + $reserva->total_productos;
                    $montoFinal = round($totalBruto * (1 - $descuento / 100), 2);
                @endphp
                
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Registrar pago</h3>
                    <p class="text-sm mb-4">
                        Servicio: Bs {{ number_format($reserva->servicio->precio, 2) }}
                        @if ($reserva->total_productos > 0)
                            <br>Productos: Bs {{ number_format($reserva->total_productos, 2) }}
                        @endif
                        @if ($descuento > 0)
                            <br>Descuento por cliente registrado ({{ $descuento }}%): -Bs {{ number_format($totalBruto - $montoFinal, 2) }}
                        @endif
                        <br><strong>Total a pagar: Bs {{ number_format($montoFinal, 2) }}</strong>
                    </p>

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('reservas.pagos.store', $reserva) }}"
                          enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Método de pago</label>
                            <select name="metodo_pago" class="mt-1 block w-full rounded border-gray-300" required>
                                <option value="QR">QR</option>
                                <option value="Efectivo">Efectivo</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Comprobante (imagen)</label>
                            <input type="file" name="comprobante" accept="image/*" class="mt-1 block w-full">
                        </div>

                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
                            Enviar comprobante
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">Pago</h3>
                    <p>
                        Monto: Bs {{ number_format($reserva->pago->monto, 2) }}
                        @if ($reserva->pago->descuento > 0)
                            <span class="text-xs text-gray-500">(incluye {{ $reserva->pago->descuento }}% de descuento)</span>
                        @endif
                    </p>
                    <p>Estado: <span class="font-semibold">{{ $reserva->pago->estado }}</span></p>
                    @if ($reserva->pago->comprobante)
                        <img src="{{ Storage::url($reserva->pago->comprobante) }}" class="mt-2 max-w-xs rounded border" alt="Comprobante">
                    @endif
                </div>
            @endif
        </div>
    </div>
</x-reserva-layout>