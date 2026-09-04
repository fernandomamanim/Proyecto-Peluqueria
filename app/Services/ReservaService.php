<?php

namespace App\Services;

use App\Models\Horario;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class ReservaService
{
    public function validarDisponibilidad(int $peluqueroId, string $fecha, string $horaInicio, string $horaFin): void
    {
        $diaSemana = Carbon::parse($fecha)->locale('es')->isoFormat('dddd');
        $diaSemana = ucfirst($diaSemana); // "lunes" -> "Lunes"

        $horario = Horario::where('peluquero_id', $peluqueroId)
            ->where('dia_semana', $diaSemana)
            ->where('estado', 'Disponible')
            ->where('hora_inicio', '<=', $horaInicio)
            ->where('hora_fin', '>=', $horaFin)
            ->first();

        if (! $horario) {
            throw ValidationException::withMessages([
                'hora_inicio' => 'El peluquero no atiende en ese horario.',
            ]);
        }

        $cruce = Reserva::where('peluquero_id', $peluqueroId)
            ->where('fecha', $fecha)
            ->whereIn('estado', ['Pendiente', 'Confirmada'])
            ->where(function ($query) use ($horaInicio, $horaFin) {
                $query->where('hora_inicio', '<', $horaFin)
                      ->where('hora_fin', '>', $horaInicio);
            })
            ->exists();

        if ($cruce) {
            throw ValidationException::withMessages([
                'hora_inicio' => 'Ya existe una reserva en ese horario para este peluquero.',
            ]);
        }
    }
}