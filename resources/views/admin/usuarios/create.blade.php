<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nuevo usuario</h2>
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

                <form method="POST" action="{{ route('admin.usuarios.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Rol</label>
                        <select name="rol_id" class="mt-1 block w-full rounded border-gray-300" required>
                            <option value="">Selecciona un rol</option>
                            @foreach ($roles as $rol)
                                <option value="{{ $rol->id }}" @selected(old('rol_id') == $rol->id)>{{ $rol->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}"
                                   class="mt-1 block w-full rounded border-gray-300" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Primer apellido</label>
                            <input type="text" name="primer_apellido" value="{{ old('primer_apellido') }}"
                                   class="mt-1 block w-full rounded border-gray-300" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Segundo apellido</label>
                        <input type="text" name="segundo_apellido" value="{{ old('segundo_apellido') }}"
                               class="mt-1 block w-full rounded border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Correo</label>
                        <input type="email" name="correo" value="{{ old('correo') }}"
                               class="mt-1 block w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}"
                               class="mt-1 block w-full rounded border-gray-300">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Contraseña</label>
                        <input type="password" name="password"
                               class="mt-1 block w-full rounded border-gray-300" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Especialidad (solo si el rol es Staff)</label>
                        <input type="text" name="especialidad" value="{{ old('especialidad') }}"
                               class="mt-1 block w-full rounded border-gray-300" placeholder="Ej: Cortes Masculinos">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Descripción (solo si el rol es Staff)</label>
                        <textarea name="descripcion" class="mt-1 block w-full rounded border-gray-300">{{ old('descripcion') }}</textarea>
                    </div>

                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Crear usuario
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>