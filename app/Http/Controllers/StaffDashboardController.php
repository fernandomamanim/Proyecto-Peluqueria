<?php

namespace App\Http\Controllers;

use App\Models\Pago;

class StaffDashboardController extends Controller
{
    public function index()
    {
        $pagosPendientes = Pago::with(['reserva.cliente', 'reserva.servicio'])
            ->where('estado', 'Pendiente')
            ->get();

        return view('staff.dashboard', compact('pagosPendientes'));
    }
}