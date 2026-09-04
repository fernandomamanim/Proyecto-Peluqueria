<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    public function run(): void
    {
        $rolAdmin = Role::where('nombre', 'Administrador')->first();

        Usuario::firstOrCreate(
            ['correo' => 'admin@caspbarber.com'],
            [
                'rol_id' => $rolAdmin->id,
                'nombre' => 'Fernando',
                'primer_apellido' => 'Mamani',
                'segundo_apellido' => 'Maldonado',
                'password' => Hash::make('password123'),
                'estado' => 'activo',
            ]
        );
    }
}