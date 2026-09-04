<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar usuario</h2>
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

                <form method="POST" action="{{ route('admin.usuarios.update', $usuario) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Rol</label>
                        <select name="rol_id" class="mt-1 block w-full rounded border-gray-300" required>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id }}" @selected($usuario->rol_id == $rol->id)>{{ $rol->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre', $usuario->nombre) }}"
                                   class="mt-1 block w-full rounded border-gray-300" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Primer apellido</label>
                            <input type="text" name="primer_apellido" value="{{ old('primer_apellido', $usuario->primer_apellido) }}"
                                   class="mt-1 block w-full rounded border-gray-300" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Segundo apellido</label>
                        <input type="text" name="segundo_apellido" value="{{ old('segundo_apellido', $usuario->segundo_apellido) }}"
                               class="mt-1 block w-full rounded border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Correo</label>
                        <input type="email" name="correo" value="{{ old('correo', $usuario->correo) }}"
                               class="mt-1 block w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $usuario->telefono) }}"
                               class="mt-1 block w-full rounded border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado</label>
                        <select name="estado" class="mt-1 block w-full rounded border-gray-300" required>
                            <option value="activo" @selected($usuario->estado === 'activo')>Activo</option>
                            <option value="inactivo" @selected($usuario->estado === 'inactivo')>Inactivo</option>
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