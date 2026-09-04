<?php

namespace Database\Seeders;

use App\Models\Horario;
use App\Models\Peluquero;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PeluqueroSeeder extends Seeder
{
    public function run(): void
    {
        $rolStaff = Role::where('nombre', 'Staff')->first();

        $usuario = Usuario::firstOrCreate(
            ['correo' => 'staff@caspbarber.com'],
            [
                'rol_id' => $rolStaff->id,
                'nombre' => 'Carlos',
                'primer_apellido' => 'Ramírez',
                'segundo_apellido' => 'Flores',
                'telefono' => '77777777',
                'password' => Hash::make('password123'),
                'estado' => 'activo',
            ]
        );

        $peluquero = Peluquero::firstOrCreate(
            ['usuario_id' => $usuario->id],
            ['especialidad' => 'Cortes Masculinos', 'descripcion' => 'Especialista en cortes modernos']
        );

        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        foreach ($dias as $dia) {
            Horario::firstOrCreate([
                'peluquero_id' => $peluquero->id,
                'dia_semana' => $dia,
            ], [
                'hora_inicio' => '08:00',
                'hora_fin' => '18:00',
            ]);
        }
    }
}