<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestión de usuarios</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Usuarios registrados</h3>
                    <a href="{{ route('admin.usuarios.create') }}"
                       class="bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">
                        Nuevo usuario
                    </a>
                </div>

                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Nombre</th>
                            <th class="py-2">Correo</th>
                            <th class="py-2">Rol</th>
                            <th class="py-2">Estado</th>
                            <th class="py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $usuario)
                            <tr class="border-b">
                                <td class="py-2">{{ $usuario->nombre }} {{ $usuario->primer_apellido }}</td>
                                <td class="py-2">{{ $usuario->correo }}</td>
                                <td class="py-2">{{ $usuario->rol->nombre }}</td>
                                <td class="py-2">
                                    <span class="px-2 py-1 rounded text-xs
                                        {{ $usuario->estado === 'activo' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($usuario->estado) }}
                                    </span>
                                </td>
                                <td class="py-2 space-x-2">
                                    <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="text-blue-600 hover:underline text-sm">
                                        Editar
                                    </a>
                                    @if ($usuario->estado === 'activo')
                                        <form method="POST" action="{{ route('admin.usuarios.destroy', $usuario) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline text-sm">
                                                Desactivar
                                            </button>
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