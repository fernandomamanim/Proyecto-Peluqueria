<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function index(Request $request)
    {
        $peluquero = $request->user()->peluquero;

        if (! $peluquero) {
            abort(403, 'Tu usuario no tiene un perfil de peluquero asociado.');
        }

        $horarios = $peluquero->horarios()->orderByRaw(
            "FIELD(dia_semana, 'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo')"
        )->get();

        return view('staff.horarios.index', compact('horarios', 'peluquero'));
    }

    public function store(Request $request)
    {
        $peluquero = $request->user()->peluquero;

        if (! $peluquero) {
            abort(403, 'Tu usuario no tiene un perfil de peluquero asociado.');
        }

        $datos = $request->validate([
            'dia_semana' => ['required', 'in:Lunes,Martes,Miércoles,Jueves,Viernes,Sábado,Domingo'],
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
        ]);

        $existe = $peluquero->horarios()->where('dia_semana', $datos['dia_semana'])->exists();
        if ($existe) {
            return back()->withErrors(['dia_semana' => 'Ya tienes un horario configurado para ese día. Edítalo en vez de crear otro.']);
        }

        $peluquero->horarios()->create($datos);

        return back()->with('success', 'Horario agregado correctamente.');
    }

    public function update(Request $request, Horario $horario)
    {
        $this->autorizar($request, $horario);

        $datos = $request->validate([
            'hora_inicio' => ['required', 'date_format:H:i'],
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
            'estado' => ['required', 'in:Disponible,NoDisponible'],
        ]);

        $horario->update($datos);

        return back()->with('success', 'Horario actualizado correctamente.');
    }

    public function destroy(Request $request, Horario $horario)
    {
        $this->autorizar($request, $horario);

        $horario->delete();

        return back()->with('success', 'Horario eliminado.');
    }

    private function autorizar(Request $request, Horario $horario): void
    {
        $peluquero = $request->user()->peluquero;

        if (! $peluquero || $horario->peluquero_id !== $peluquero->id) {
            abort(403, 'No puedes modificar el horario de otro peluquero.');
        }
    }
}