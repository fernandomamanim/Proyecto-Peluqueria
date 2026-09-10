<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Horario;
use App\Models\Peluquero;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function index(Peluquero $peluquero)
    {
        $horarios = $peluquero->horarios()->orderByRaw(
            "FIELD(dia_semana, 'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo')"
        )->get();

        return view('admin.horarios.index', compact('horarios', 'peluquero'));
    }

    public function store(Request $request, Peluquero $peluquero)
    {
        $datos = $request->validate([
            'dia_semana' => ['required', 'in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
        ]);

        $existe = $peluquero->horarios()->where('dia_semana', $datos['dia_semana'])->exists();
        if ($existe) {
            return back()->withErrors(['dia_semana' => 'Ese peluquero ya tiene un horario configurado para ese día.']);
        }

        $peluquero->horarios()->create($datos);

        return back()->with('success', 'Horario agregado correctamente.');
    }

    public function update(Request $request, Peluquero $peluquero, Horario $horario)
    {
        abort_if($horario->peluquero_id !== $peluquero->id, 404);

        $datos = $request->validate([
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'estado' => ['required', 'in:Disponible,NoDisponible'],
        ]);

        $horario->update($datos);

        return back()->with('success', 'Horario actualizado correctamente.');
    }

    public function destroy(Peluquero $peluquero, Horario $horario)
    {
        abort_if($horario->peluquero_id !== $peluquero->id, 404);

        $horario->delete();

        return back()->with('success', 'Horario eliminado.');
    }
}