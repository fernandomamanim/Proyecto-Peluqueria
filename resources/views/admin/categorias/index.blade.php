<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Categorías de servicio</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Nueva categoría</h3>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.categorias.store') }}" class="flex gap-4 items-end">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre') }}"
                               class="mt-1 block w-full rounded border-gray-300" required>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <input type="text" name="descripcion" value="{{ old('descripcion') }}"
                               class="mt-1 block w-full rounded border-gray-300">
                    </div>
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Agregar
                    </button>
                </form>
            </div>

            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Categorías existentes</h3>
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Nombre</th>
                            <th class="py-2"># Servicios</th>
                            <th class="py-2">Estado</th>
                            <th class="py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categorias as $categoria)
                            <tr class="border-b">
                                <td class="py-2">{{ $categoria->nombre }}</td>
                                <td class="py-2">{{ $categoria->servicios_count }}</td>
                                <td class="py-2">{{ ucfirst($categoria->estado) }}</td>
                                <td class="py-2">
                                    <form method="POST" action="{{ route('admin.categorias.update', $categoria) }}" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="nombre" value="{{ $categoria->nombre }}">
                                        <input type="hidden" name="descripcion" value="{{ $categoria->descripcion }}">
                                        <input type="hidden" name="estado" value="{{ $categoria->estado === 'activo' ? 'inactivo' : 'activo' }}">
                                        <button type="submit" class="text-blue-600 hover:underline text-sm">
                                            {{ $categoria->estado === 'activo' ? 'Desactivar' : 'Activar' }}
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