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
            'hora_fin' => ['required', 'date_format:H:i', 'after:hora_inicio'],
        ]);

        $this->reservaService->validarDisponibilidad(
            $datos['peluquero_id'],
            $datos['fecha'],
            $datos['hora_inicio'],
            $datos['hora_fin'],
        );

        $reserva = Reserva::create([
            ...$datos,
            'usuario_id' => $request->user()->id,
            'codigo_qr' => 'QR-RES-'.Str::upper(Str::random(10)),
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