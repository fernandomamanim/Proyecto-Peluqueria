<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar servicio</h2>
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

                @if ($servicio->imagen)
                    <img src="{{ Storage::url($servicio->imagen) }}" class="w-32 h-32 object-cover rounded mb-4" alt="Imagen actual">
                @endif

                <form method="POST" action="{{ route('admin.servicios.update', $servicio) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Categoría</label>
                        <select name="categoria_servicio_id" class="mt-1 block w-full rounded border-gray-300">
                            <option value="">Sin categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id }}" @selected($servicio->categoria_servicio_id == $categoria->id)>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" name="nombre" value="{{ old('nombre', $servicio->nombre) }}"
                               class="mt-1 block w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea name="descripcion" class="mt-1 block w-full rounded border-gray-300">{{ old('descripcion', $servicio->descripcion) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Precio (Bs)</label>
                            <input type="number" step="0.01" name="precio" value="{{ old('precio', $servicio->precio) }}"
                                   class="mt-1 block w-full rounded border-gray-300" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Duración (minutos)</label>
                            <input type="number" name="duracion" value="{{ old('duracion', $servicio->duracion) }}"
                                   class="mt-1 block w-full rounded border-gray-300" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nueva imagen (opcional)</label>
                        <input type="file" name="imagen" accept="image/*" class="mt-1 block w-full">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="estado" class="mt-1 block w-full rounded border-gray-300" required>
                            <option value="activo" @selected($servicio->estado === 'activo')>Activo</option>
                            <option value="inactivo" @selected($servicio->estado === 'inactivo')>Inactivo</option>
                        </select>
                    </div>

                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Guardar cambios
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>