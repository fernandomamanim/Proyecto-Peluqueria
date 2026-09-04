<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nombre' => 'Administrador', 'descripcion' => 'Acceso total al sistema'],
            ['nombre' => 'Staff', 'descripcion' => 'Gestiona reservas, servicios y reportes asignados'],
            ['nombre' => 'Cliente', 'descripcion' => 'Realiza reservas y compras'],
        ];

        foreach ($roles as $rol) {
            Role::firstOrCreate(['nombre' => $rol['nombre']], $rol);
        }
    }
}