<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reporte de Inventario</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <x-filtro-fechas :desde="$desde" :hasta="$hasta" :pdf-route="route('admin.reportes.inventario.pdf')" />

            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <p class="text-sm text-gray-500">Valor total del inventario (precio x stock)</p>
                <p class="text-2xl font-semibold">Bs {{ number_format($valorInventario, 2) }}</p>
            </div>

            @if ($productosStockBajo->isNotEmpty())
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 mb-6 text-sm">
                    <strong>Stock bajo (≤5 unidades):</strong>
                    {{ $productosStockBajo->pluck('nombre')->join(', ') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <h3 class="font-semibold mb-4">Stock actual</h3>
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Producto</th>
                            <th class="py-2">Proveedor</th>
                            <th class="py-2">Precio</th>
                            <th class="py-2">Stock</th>
                            <th class="py-2">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                            <tr class="border-b">
                                <td class="py-2">{{ $producto->nombre }}</td>
                                <td class="py-2">{{ $producto->proveedor?->nombre ?? '—' }}</td>
                                <td class="py-2">Bs {{ $producto->precio }}</td>
                                <td class="py-2 {{ $producto->stock <= 5 ? 'text-red-600 font-semibold' : '' }}">{{ $producto->stock }}</td>
                                <td class="py-2">Bs {{ number_format($producto->precio * $producto->stock, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold mb-4">Movimientos en el periodo</h3>
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Fecha</th>
                            <th class="py-2">Producto</th>
                            <th class="py-2">Tipo</th>
                            <th class="py-2">Cantidad</th>
                            <th class="py-2">Motivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($movimientos as $mov)
                            <tr class="border-b">
                                <td class="py-2">{{ $mov->fecha_movimiento->format('d/m/Y H:i') }}</td>
                                <td class="py-2">{{ $mov->producto->nombre }}</td>
                                <td class="py-2">{{ $mov->tipo_movimiento }}</td>
                                <td class="py-2">{{ $mov->cantidad }}</td>
                                <td class="py-2">{{ $mov->motivo }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="py-4 text-gray-500 text-center">Sin movimientos en este periodo.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>