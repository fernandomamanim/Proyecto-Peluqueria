<?php

namespace App\Console\Commands;

use App\Models\Reserva;
use Illuminate\Console\Command;

class FinalizarReservas extends Command
{
    protected $signature = 'reservas:finalizar';
    protected $description = 'Marca como Finalizada las reservas Confirmadas cuya fecha y hora ya pasaron';

    public function handle(): void
    {
        $afectadas = Reserva::where('estado', 'Confirmada')
            ->where(function ($query) {
                $query->where('fecha', '<', now()->toDateString())
                    ->orWhere(function ($q) {
                        $q->where('fecha', now()->toDateString())
                          ->where('hora_fin', '<=', now()->toTimeString());
                    });
            })
            ->update(['estado' => 'Finalizada']);

        $this->info("Reservas finalizadas: {$afectadas}");
    }
}