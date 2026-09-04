<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Reserva;
use App\Models\Usuario;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsuarios = Usuario::where('estado', 'activo')->count();
        $reservasHoy = Reserva::whereDate('fecha', today())->count();
        $pagosPendientes = Pago::where('estado', 'Pendiente')->count();
        $ingresosMes = Pago::where('estado', 'Aprobado')
            ->whereMonth('fecha_pago', now()->month)
            ->whereYear('fecha_pago', now()->year)
            ->sum('monto');

        return view('admin.dashboard', compact('totalUsuarios', 'reservasHoy', 'pagosPendientes', 'ingresosMes'));
    }
}