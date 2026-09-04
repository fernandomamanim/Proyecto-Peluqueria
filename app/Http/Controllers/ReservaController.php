<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Services\ReservaService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReservaController extends Controller
{
    public function __construct(protected ReservaService $reservaService)
    {
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'peluquero_id' => ['required', 'exists:peluqueros,id'],
            'servicio_id' => ['required', 'exists:servicios,id'],
            'fecha' => ['required', 'date', 'after_or_equal:today'],
            'hora_inicio' => ['required', 'date_format:H:i'],
        ]);
    
        $servicio = \App\Models\Servicio::findOrFail($datos['servicio_id']);
    
        $horaInicio = \Carbon\Carbon::createFromFormat('H:i', $datos['hora_inicio']);
        $horaFin = $horaInicio->copy()->addMinutes($servicio->duracion);
    
        $this->reservaService->validarDisponibilidad(
            $datos['peluquero_id'],
            $datos['fecha'],
            $horaInicio->format('H:i'),
            $horaFin->format('H:i'),
        );
    
        $reserva = Reserva::create([
            'usuario_id' => $request->user()->id,
            'peluquero_id' => $datos['peluquero_id'],
            'servicio_id' => $datos['servicio_id'],
            'fecha' => $datos['fecha'],
            'hora_inicio' => $horaInicio->format('H:i'),
            'hora_fin' => $horaFin->format('H:i'),
            'codigo_qr' => 'QR-RES-'.\Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(10)),
            'fecha_expiracion' => now()->addMinutes(10),
        ]);
    
        return redirect()
            ->route('cliente.reservas.show', $reserva)
            ->with('success', 'Reserva creada correctamente.');
    }

    public function index(Request $request)
    {
        $reservas = $request->user()
            ->reservas() // ver Paso 38, hay que agregar esta relación
            ->with(['servicio', 'peluquero.usuario'])
            ->orderByDesc('fecha')
            ->get();
    
        return view('cliente.dashboard', compact('reservas'));
    }
    
    public function create()
    {
        $servicios = \App\Models\Servicio::where('estado', 'activo')->get();
        $peluqueros = \App\Models\Peluquero::with('usuario')->where('estado', 'activo')->get();
    
        return view('cliente.reservas.create', compact('servicios', 'peluqueros'));
    }
    
    public function show(Reserva $reserva)
    {
        abort_if($reserva->usuario_id !== auth()->id(), 403);
    
        $reserva->load(['servicio', 'peluquero.usuario', 'pago']);
    
        return view('cliente.reservas.show', compact('reserva'));
    }
}