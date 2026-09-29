<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Reserva;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    private const PORCENTAJE_DESCUENTO_CLIENTE = 8.00;

    public function store(Request $request, Reserva $reserva)
    {
        if ($reserva->usuario_id) {
            abort_if(! $request->user() || $request->user()->id !== $reserva->usuario_id, 403);
        }

        $datos = $request->validate([
            'metodo_pago' => ['required', 'in:QR,Efectivo'],
            'comprobante' => ['required_if:metodo_pago,QR', 'file', 'image', 'max:4096'],
        ]);

        $descuento = $reserva->usuario_id ? self::PORCENTAJE_DESCUENTO_CLIENTE : 0;
        $monto = round($reserva->servicio->precio * (1 - $descuento / 100), 2);

        $rutaComprobante = null;
        if ($request->hasFile('comprobante')) {
            $rutaComprobante = $request->file('comprobante')->store('comprobantes', 'public');
        }

        Pago::create([
            'reserva_id' => $reserva->id,
            'monto' => $monto,
            'descuento' => $descuento,
            'metodo_pago' => $datos['metodo_pago'],
            'comprobante' => $rutaComprobante,
            'estado' => 'Pendiente',
        ]);

        return redirect()
            ->route('reservas.show', $reserva)
            ->with('success', 'Comprobante subido. Tu pago está pendiente de aprobación.');
    }

    public function aprobar(Request $request, Pago $pago)
    {
        $this->autorizarStaff($request);

        $pago->update(['estado' => 'Aprobado', 'fecha_pago' => now()]);
        $pago->reserva->update(['estado' => 'Confirmada']);

        return back()->with('success', 'Pago aprobado y reserva confirmada.');
    }

    public function rechazar(Request $request, Pago $pago)
    {
        $this->autorizarStaff($request);

        $datos = $request->validate(['observaciones' => ['nullable', 'string', 'max:500']]);

        $pago->update(['estado' => 'Rechazado', 'observaciones' => $datos['observaciones'] ?? null]);

        return back()->with('success', 'Pago rechazado.');
    }

    private function autorizarStaff(Request $request): void
    {
        $usuario = $request->user();
        if (! $usuario || ! in_array($usuario->rol->nombre, ['Staff', 'Administrador'])) {
            abort(403, 'No tienes permiso para gestionar pagos.');
        }
    }
    public function reactivar(Request $request, Pago $pago)
    {
        $this->autorizarStaff($request);
    
        if ($pago->estado === 'Aprobado') {
            $pago->reserva->update(['estado' => 'Pendiente']);
        }
    
        $pago->update(['estado' => 'Pendiente', 'fecha_pago' => null, 'observaciones' => null]);
    
        return back()->with('success', 'Pago reactivado — vuelve a estar pendiente de revisión.');
    }
}