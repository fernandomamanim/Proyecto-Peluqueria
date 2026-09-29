<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Reserva;
use Illuminate\Http\Request;

class StaffReservaController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();

        $query = Reserva::with(['servicio', 'peluquero.usuario', 'pago', 'reservaProductos.producto'])
            ->whereIn('estado', ['Pendiente', 'Confirmada']);

        if ($usuario->esStaff()) {
            $peluquero = $usuario->peluquero;
            abort_if(! $peluquero, 403);
            $query->where('peluquero_id', $peluquero->id);
        }

        $reservas = $query->orderBy('fecha')->orderBy('hora_inicio')->get();

        return view('staff.reservas.index', compact('reservas'));
    }

    public function show(Request $request, Reserva $reserva)
    {
        $this->autorizar($request, $reserva);

        $reserva->load(['servicio', 'peluquero.usuario', 'pago', 'reservaProductos.producto']);
        $productos = Producto::where('estado', 'activo')->where('stock', '>', 0)->get();

        return view('staff.reservas.show', compact('reserva', 'productos'));
    }

    private function autorizar(Request $request, Reserva $reserva): void
    {
        $usuario = $request->user();

        if ($usuario->esStaff()) {
            $peluquero = $usuario->peluquero;
            abort_if(! $peluquero || $reserva->peluquero_id !== $peluquero->id, 403);
        }
    }
}