<?php

namespace App\Http\Controllers;

use App\Models\Peluquero;
use App\Models\Reserva;
use App\Models\Servicio;
use App\Services\ReservaService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReservaController extends Controller
{
    public function __construct(protected ReservaService $reservaService)
    {
    }

    public function index(Request $request)
    {
        $reservas = $request->user()
            ->reservas()
            ->with(['servicio', 'peluquero.usuario', 'pago'])
            ->orderByDesc('fecha')
            ->get();

        return view('cliente.dashboard', compact('reservas'));
    }

    public function create()
    {
        $servicios = Servicio::where('estado', 'activo')->get();
        $peluqueros = Peluquero::with('usuario')->where('estado', 'activo')->get();

        return view('reservas.create', compact('servicios', 'peluqueros'));
    }

    public function store(Request $request)
    {
        $reglas = [
            'peluquero_id' => ['required', 'exists:peluqueros,id'],
            'servicio_id' => ['required', 'exists:servicios,id'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora_inicio' => ['required', 'date_format:H:i'],
        ];

        if (! $request->user()) {
            $reglas['nombre_invitado'] = ['required', 'string', 'max:150'];
            $reglas['telefono_invitado'] = ['required', 'string', 'max:20'];
        }

        $datos = $request->validate($reglas);

        $servicio = Servicio::findOrFail($datos['servicio_id']);

        $horaInicio = \Carbon\Carbon::createFromFormat('H:i', $datos['hora_inicio']);
        $horaFin = $horaInicio->copy()->addMinutes($servicio->duracion);

        $this->reservaService->validarDisponibilidad(
            $datos['peluquero_id'],
            $datos['fecha'],
            $horaInicio->format('H:i'),
            $horaFin->format('H:i'),
        );

        $reserva = Reserva::create([
            'usuario_id' => $request->user()?->id,
            'nombre_invitado' => $datos['nombre_invitado'] ?? null,
            'telefono_invitado' => $datos['telefono_invitado'] ?? null,
            'peluquero_id' => $datos['peluquero_id'],
            'servicio_id' => $datos['servicio_id'],
            'fecha' => $datos['fecha'],
            'hora_inicio' => $horaInicio->format('H:i'),
            'hora_fin' => $horaFin->format('H:i'),
            'codigo_qr' => 'QR-RES-'.Str::upper(Str::random(10)),
            'fecha_expiracion' => now()->addMinutes(10),
        ]);

        return redirect()
            ->route('reservas.show', $reserva)
            ->with('success', 'Reserva creada correctamente.');
    }

    public function show(Request $request, Reserva $reserva)
    {
        if ($reserva->usuario_id) {
            abort_if(! $request->user() || $request->user()->id !== $reserva->usuario_id, 403);
        }

        $reserva->load(['servicio', 'peluquero.usuario', 'pago']);

        return view('reservas.show', compact('reserva'));
    }
    public function disponibilidad(Request $request)
    {
        $datos = $request->validate([
            'peluquero_id' => ['required', 'exists:peluqueros,id'],
            'fecha' => ['required', 'date'],
        ]);
    
        $diasSemana = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $diaSemana = $diasSemana[\Carbon\Carbon::parse($datos['fecha'])->dayOfWeek];
    
        $horario = \App\Models\Horario::where('peluquero_id', $datos['peluquero_id'])
            ->where('dia_semana', $diaSemana)
            ->where('estado', 'Disponible')
            ->first();
    
        $ocupados = \App\Models\Reserva::where('peluquero_id', $datos['peluquero_id'])
            ->where('fecha', $datos['fecha'])
            ->whereIn('estado', ['Pendiente', 'Confirmada'])
            ->orderBy('hora_inicio')
            ->get(['hora_inicio', 'hora_fin'])
            ->map(fn ($r) => [
                'inicio' => substr($r->hora_inicio, 0, 5),
                'fin' => substr($r->hora_fin, 0, 5),
            ]);
    
        return response()->json([
            'dia_semana' => $diaSemana,
            'atiende' => (bool) $horario,
            'horario_inicio' => $horario ? substr($horario->hora_inicio, 0, 5) : null,
            'horario_fin' => $horario ? substr($horario->hora_fin, 0, 5) : null,
            'ocupados' => $ocupados,
        ]);
    }
}