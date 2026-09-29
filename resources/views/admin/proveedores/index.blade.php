<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Proveedores</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Nuevo proveedor</h3>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.proveedores.store') }}" class="grid grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}" class="mt-1 block w-full rounded border-gray-300" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">NIT</label>
                        <input type="text" name="nit" value="{{ old('nit') }}" class="mt-1 block w-full rounded border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}" class="mt-1 block w-full rounded border-gray-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Correo</label>
                        <input type="email" name="correo" value="{{ old('correo') }}" class="mt-1 block w-full rounded border-gray-300">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Dirección</label>
                        <input type="text" name="direccion" value="{{ old('direccion') }}" class="mt-1 block w-full rounded border-gray-300">
                    </div>
                    <div class="col-span-2">
                        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
                            Agregar
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Proveedores existentes</h3>
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Nombre</th>
                            <th class="py-2">Teléfono</th>
                            <th class="py-2"># Productos</th>
                            <th class="py-2">Estado</th>
                            <th class="py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($proveedores as $proveedor)
                            <tr class="border-b">
                                <td class="py-2">{{ $proveedor->nombre }}</td>
                                <td class="py-2">{{ $proveedor->telefono ?? '—' }}</td>
                                <td class="py-2">{{ $proveedor->productos_count }}</td>
                                <td class="py-2">{{ ucfirst($proveedor->estado) }}</td>
                                <td class="py-2">
                                    <form method="POST" action="{{ route('admin.proveedores.update', $proveedor) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="nombre" value="{{ $proveedor->nombre }}">
                                        <input type="hidden" name="nit" value="{{ $proveedor->nit }}">
                                        <input type="hidden" name="telefono" value="{{ $proveedor->telefono }}">
                                        <input type="hidden" name="correo" value="{{ $proveedor->correo }}">
                                        <input type="hidden" name="direccion" value="{{ $proveedor->direccion }}">
                                        <input type="hidden" name="estado" value="{{ $proveedor->estado === 'activo' ? 'inactivo' : 'activo' }}">
                                        <button type="submit" class="text-blue-600 hover:underline text-sm">
                                            {{ $proveedor->estado === 'activo' ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>