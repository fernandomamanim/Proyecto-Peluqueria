<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaController extends Controller
{
    public function index(Request $request)
    {
        $query = Reserva::with(['cliente', 'peluquero.usuario', 'servicio', 'pago']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('peluquero_id')) {
            $query->where('peluquero_id', $request->peluquero_id);
        }

        $reservas = $query->orderByDesc('fecha')->orderByDesc('hora_inicio')->get();

        $peluqueros = \App\Models\Peluquero::with('usuario')->get();

        return view('admin.reservas.index', compact('reservas', 'peluqueros'));
    }
}