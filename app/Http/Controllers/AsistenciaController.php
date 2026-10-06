<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    public function index(Request $request)
    {
        $peluquero = $request->user()->peluquero;
        abort_if(! $peluquero, 403, 'Tu usuario no tiene un perfil de peluquero asociado.');

        $asistenciaHoy = Asistencia::firstOrNew([
            'peluquero_id' => $peluquero->id,
            'fecha' => now()->toDateString(),
        ]);

        $historial = Asistencia::where('peluquero_id', $peluquero->id)
            ->orderByDesc('fecha')
            ->limit(15)
            ->get();

        return view('staff.asistencia.index', compact('asistenciaHoy', 'historial'));
    }

    public function marcarEntrada(Request $request)
    {
        $peluquero = $request->user()->peluquero;
        abort_if(! $peluquero, 403);

        $asistencia = Asistencia::firstOrCreate([
            'peluquero_id' => $peluquero->id,
            'fecha' => now()->toDateString(),
        ]);

        if ($asistencia->hora_entrada) {
            return back()->withErrors(['entrada' => 'Ya marcaste tu entrada hoy.']);
        }

        $asistencia->update(['hora_entrada' => now()->format('H:i:s')]);

        return back()->with('success', 'Entrada registrada: '.now()->format('H:i'));
    }

    public function marcarSalida(Request $request)
    {
        $peluquero = $request->user()->peluquero;
        abort_if(! $peluquero, 403);

        $asistencia = Asistencia::where('peluquero_id', $peluquero->id)
            ->where('fecha', now()->toDateString())
            ->first();

        if (! $asistencia || ! $asistencia->hora_entrada) {
            return back()->withErrors(['salida' => 'Debes marcar tu entrada primero.']);
        }

        if ($asistencia->hora_salida) {
            return back()->withErrors(['salida' => 'Ya marcaste tu salida hoy.']);
        }

        $asistencia->update(['hora_salida' => now()->format('H:i:s')]);

        return back()->with('success', 'Salida registrada: '.now()->format('H:i'));
    }
}