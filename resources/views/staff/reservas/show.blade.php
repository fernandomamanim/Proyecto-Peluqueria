<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reserva #{{ $reserva->id }} — {{ $reserva->nombre_cliente }}</h2>
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
                <dl class="grid grid-cols-2 gap-2 text-sm">
                    <dt class="text-gray-500">Servicio</dt>
                    <dd>{{ $reserva->servicio->nombre }} — Bs {{ $reserva->servicio->precio }}</dd>
                    <dt class="text-gray-500">Fecha</dt>
                    <dd>{{ $reserva->fecha->format('d/m/Y') }} {{ substr($reserva->hora_inicio, 0, 5) }}</dd>
                    <dt class="text-gray-500">Estado</dt>
                    <dd>{{ $reserva->estado }}</dd>
                </dl>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Productos usados/vendidos</h3>

                @if ($reserva->pago)
                    <p class="text-sm text-gray-500 mb-4">Esta reserva ya tiene un pago registrado — no se pueden modificar los productos.</p>
                @else
                    <form method="POST" action="{{ route('staff.reservas.productos.store', $reserva) }}" class="flex gap-4 items-end mb-4">
                        @csrf
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Producto</label>
                            <select name="producto_id" class="mt-1 block w-full rounded border-gray-300" required>
                                <option value="">Selecciona un producto</option>
                                @foreach ($productos as $producto)
                                    <option value="{{ $producto->id }}">
                                        {{ $producto->nombre }} — Bs {{ $producto->precio }} (stock: {{ $producto->stock }})
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
                @endif

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
                        @forelse ($reserva->reservaProductos as $rp)
                            <tr class="border-b">
                                <td class="py-2">{{ $rp->producto->nombre }}</td>
                                <td class="py-2">{{ $rp->cantidad }}</td>
                                <td class="py-2">Bs {{ $rp->subtotal }}</td>
                                <td class="py-2">
                                    @unless ($reserva->pago)
                                        <form method="POST" action="{{ route('staff.reservas.productos.destroy', [$reserva, $rp]) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline text-xs">Quitar</button>
                                        </form>
                                    @endunless
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-4 text-gray-500 text-center">Sin productos agregados.</td></tr>
                        @endforelse
                    </tbody>
                    @if ($reserva->reservaProductos->isNotEmpty())
                        <tfoot>
                            <tr class="border-t font-semibold">
                                <td class="py-2" colspan="2">Total productos</td>
                                <td class="py-2">Bs {{ $reserva->total_productos }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</x-app-layout>