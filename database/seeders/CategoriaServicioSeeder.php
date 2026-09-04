<?php

namespace Database\Seeders;

use App\Models\CategoriaServicio;
use Illuminate\Database\Seeder;

class CategoriaServicioSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = ['Cortes', 'Coloración', 'Tratamientos', 'Barbería'];

        foreach ($categorias as $nombre) {
            CategoriaServicio::firstOrCreate(['nombre' => $nombre]);
        }
    }
}