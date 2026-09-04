<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Reserva;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function store(Request $request, Reserva $reserva)
    {
        $datos = $request->validate([
            'monto' => ['required', 'numeric', 'min:0'],
            'metodo_pago' => ['required', 'in:QR,Efectivo'],
            'comprobante' => ['required_if:metodo_pago,QR', 'file', 'image', 'max:4096'],
        ]);

        if ($reserva->usuario_id !== $request->user()->id) {
            abort(403, 'No puedes registrar un pago para una reserva que no es tuya.');
        }

        $rutaComprobante = null;
        if ($request->hasFile('comprobante')) {
            $rutaComprobante = $request->file('comprobante')->store('comprobantes', 'public');
        }

        $pago = Pago::create([
            'reserva_id' => $reserva->id,
            'monto' => $datos['monto'],
            'metodo_pago' => $datos['metodo_pago'],
            'comprobante' => $rutaComprobante,
            'estado' => 'Pendiente',
        ]);

        return redirect()
            ->route('cliente.reservas.show', $reserva)
            ->with('success', 'Comprobante subido. Tu pago está pendiente de aprobación.');
    }

    public function aprobar(Request $request, Pago $pago)
    {
        $this->autorizarStaff($request);

        $pago->update([
            'estado' => 'Aprobado',
            'fecha_pago' => now(),
        ]);

        $pago->reserva->update(['estado' => 'Confirmada']);

        return back()->with('success', 'Pago aprobado y reserva confirmada.');
    }

    public function rechazar(Request $request, Pago $pago)
    {
        $this->autorizarStaff($request);

        $datos = $request->validate([
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        $pago->update([
            'estado' => 'Rechazado',
            'observaciones' => $datos['observaciones'] ?? null,
        ]);

        return back()->with('success', 'Pago rechazado.');
    }

    private function autorizarStaff(Request $request): void
    {
        $usuario = $request->user();
        if (! $usuario || ! in_array($usuario->rol->nombre, ['Staff', 'Administrador'])) {
            abort(403, 'No tienes permiso para gestionar pagos.');
        }
    }
}