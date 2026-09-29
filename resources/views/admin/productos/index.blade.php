<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Productos</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Catálogo de productos</h3>
                    <div class="space-x-2">
                        <a href="{{ route('admin.proveedores.index') }}" class="text-gray-600 hover:underline text-sm">
                            Gestionar proveedores
                        </a>
                        <a href="{{ route('admin.productos.create') }}" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
                            Nuevo producto
                        </a>
                    </div>
                </div>

                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Nombre</th>
                            <th class="py-2">Proveedor</th>
                            <th class="py-2">Precio</th>
                            <th class="py-2">Stock</th>
                            <th class="py-2">Estado</th>
                            <th class="py-2">Ajustar stock</th>
                            <th class="py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                            <tr class="border-b">
                                <td class="py-2">{{ $producto->nombre }}</td>
                                <td class="py-2">{{ $producto->proveedor?->nombre ?? '—' }}</td>
                                <td class="py-2">Bs {{ $producto->precio }}</td>
                                <td class="py-2 {{ $producto->stock <= 5 ? 'text-red-600 font-semibold' : '' }}">
                                    {{ $producto->stock }}
                                </td>
                                <td class="py-2">
                                    <span class="px-2 py-1 rounded text-xs {{ $producto->estado === 'activo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($producto->estado) }}
                                    </span>
                                </td>
                                <td class="py-2">
                                    <form method="POST" action="{{ route('admin.productos.ajustar-stock', $producto) }}" class="flex gap-1 items-center">
                                        @csrf
                                        @method('PATCH')
                                        <select name="tipo_movimiento" class="rounded border-gray-300 text-xs">
                                            <option value="Entrada">+</option>
                                            <option value="Salida">-</option>
                                        </select>
                                        <input type="number" name="cantidad" min="1" class="w-16 rounded border-gray-300 text-xs" required>
                                        <button type="submit" class="text-blue-600 hover:underline text-xs">Aplicar</button>
                                    </form>
                                </td>
                                <td class="py-2 space-x-2">
                                    <a href="{{ route('admin.productos.edit', $producto) }}" class="text-blue-600 hover:underline text-sm">Editar</a>
                                    @if ($producto->estado === 'activo')
                                        <form method="POST" action="{{ route('admin.productos.destroy', $producto) }}" class="inline"
                                              onsubmit="return confirm('¿Desactivar {{ $producto->nombre }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline text-sm">Desactivar</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>