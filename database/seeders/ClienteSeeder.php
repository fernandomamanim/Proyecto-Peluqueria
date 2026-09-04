<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClienteSeeder extends Seeder
{
    public function run(): void
    {
        $rolCliente = Role::where('nombre', 'Cliente')->first();

        Usuario::firstOrCreate(
            ['correo' => 'cliente@caspbarber.com'],
            [
                'rol_id' => $rolCliente->id,
                'nombre' => 'María',
                'primer_apellido' => 'Gómez',
                'segundo_apellido' => 'Rojas',
                'telefono' => '71234567',
                'password' => Hash::make('password123'),
                'estado' => 'activo',
            ]
        );
    }
}