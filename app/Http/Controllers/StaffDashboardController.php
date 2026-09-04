<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();

        $query = Pago::with(['reserva.cliente', 'reserva.servicio'])
            ->where('estado', 'Pendiente');

        // El Administrador ve todos los pagos; el Staff solo los de sus propias reservas
        if ($usuario->esStaff()) {
            $peluquero = $usuario->peluquero;

            if (! $peluquero) {
                abort(403, 'Tu usuario no tiene un perfil de peluquero asociado.');
            }

            $query->whereHas('reserva', function ($q) use ($peluquero) {
                $q->where('peluquero_id', $peluquero->id);
            });
        }

        $pagosPendientes = $query->get();

        return view('staff.dashboard', compact('pagosPendientes'));
    }
}