<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Peluqueros</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-6">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b">
                            <th class="py-2">Nombre</th>
                            <th class="py-2">Especialidad</th>
                            <th class="py-2">Horarios configurados</th>
                            <th class="py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($peluqueros as $peluquero)
                            <tr class="border-b">
                                <td class="py-2">{{ $peluquero->usuario->nombre }} {{ $peluquero->usuario->primer_apellido }}</td>
                                <td class="py-2">{{ $peluquero->especialidad ?? '—' }}</td>
                                <td class="py-2">{{ $peluquero->horarios_count }}</td>
                                <td class="py-2">
                                    <a href="{{ route('admin.peluqueros.horarios.index', $peluquero) }}" class="text-blue-600 hover:underline text-sm">
                                        Gestionar horarios
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>