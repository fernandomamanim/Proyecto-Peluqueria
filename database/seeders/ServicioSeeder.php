<?php

namespace Database\Seeders;

use App\Models\CategoriaServicio;
use App\Models\Servicio;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    public function run(): void
    {
        $cortes = CategoriaServicio::where('nombre', 'Cortes')->first();
        $barberia = CategoriaServicio::where('nombre', 'Barbería')->first();

        $servicios = [
            ['nombre' => 'Corte Caballero', 'descripcion' => 'Corte clásico', 'precio' => 25, 'duracion' => 30, 'categoria_servicio_id' => $cortes->id],
            ['nombre' => 'Corte Dama', 'descripcion' => 'Corte con lavado', 'precio' => 40, 'duracion' => 60, 'categoria_servicio_id' => $cortes->id],
            ['nombre' => 'Barba', 'descripcion' => 'Perfilado de barba', 'precio' => 15, 'duracion' => 20, 'categoria_servicio_id' => $barberia->id],
        ];

        foreach ($servicios as $servicio) {
            Servicio::firstOrCreate(['nombre' => $servicio['nombre']], $servicio);
        }
    }
}