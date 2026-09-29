<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();

        $base = Pago::with(['reserva.servicio']);

        if ($usuario->esStaff()) {
            $peluquero = $usuario->peluquero;

            if (! $peluquero) {
                abort(403, 'Tu usuario no tiene un perfil de peluquero asociado.');
            }

            $base->whereHas('reserva', function ($q) use ($peluquero) {
                $q->where('peluquero_id', $peluquero->id);
            });
        }

        $pagosPendientes = (clone $base)->where('estado', 'Pendiente')->get();
        $pagosRevisados = (clone $base)->whereIn('estado', ['Aprobado', 'Rechazado'])
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get();

        return view('staff.dashboard', compact('pagosPendientes', 'pagosRevisados'));
    }
}