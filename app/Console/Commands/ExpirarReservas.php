<?php

namespace App\Console\Commands;

use App\Models\Reserva;
use Illuminate\Console\Command;

class ExpirarReservas extends Command
{
    protected $signature = 'reservas:expirar';
    protected $description = 'Marca como Expirada las reservas Pendientes cuya fecha de expiración ya pasó';

    public function handle(): void
    {
        $afectadas = Reserva::where('estado', 'Pendiente')
            ->where('fecha_expiracion', '<', now())
            ->update(['estado' => 'Expirada']);

        $this->info("Reservas expiradas: {$afectadas}");
    }
}