<?php

namespace App\Http\Controllers;

use App\Models\MovimientoInventario;
use App\Models\Producto;
use App\Models\Reserva;
use App\Models\ReservaProducto;
use Illuminate\Http\Request;

class ReservaProductoController extends Controller
{
    public function store(Request $request, Reserva $reserva)
    {
        $this->autorizar($request, $reserva);

        abort_if($reserva->pago, 422, 'No se pueden agregar productos: esta reserva ya tiene un pago registrado.');

        $datos = $request->validate([
            'producto_id' => ['required', 'exists:productos,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
        ]);

        $producto = Producto::findOrFail($datos['producto_id']);

        if ($datos['cantidad'] > $producto->stock) {
            return back()->withErrors(['cantidad' => 'No hay suficiente stock de ese producto.']);
        }

        ReservaProducto::create([
            'reserva_id' => $reserva->id,
            'producto_id' => $producto->id,
            'cantidad' => $datos['cantidad'],
            'precio' => $producto->precio,
            'subtotal' => $producto->precio * $datos['cantidad'],
        ]);

        $producto->decrement('stock', $datos['cantidad']);

        MovimientoInventario::create([
            'producto_id' => $producto->id,
            'usuario_id' => $request->user()?->id,
            'tipo_movimiento' => 'Salida',
            'cantidad' => $datos['cantidad'],
            'motivo' => 'Venta durante reserva #'.$reserva->id,
            'fecha_movimiento' => now(),
        ]);

        return back()->with('success', 'Producto agregado a la reserva.');
    }

    public function destroy(Request $request, Reserva $reserva, ReservaProducto $reservaProducto)
    {
        $this->autorizar($request, $reserva);

        abort_if($reserva->pago, 422, 'No se pueden quitar productos: esta reserva ya tiene un pago registrado.');
        abort_if($reservaProducto->reserva_id !== $reserva->id, 404);

        $reservaProducto->producto->increment('stock', $reservaProducto->cantidad);

        MovimientoInventario::create([
            'producto_id' => $reservaProducto->producto_id,
            'usuario_id' => $request->user()?->id,
            'tipo_movimiento' => 'Entrada',
            'cantidad' => $reservaProducto->cantidad,
            'motivo' => 'Producto removido de reserva #'.$reserva->id,
            'fecha_movimiento' => now(),
        ]);

        $reservaProducto->delete();

        return back()->with('success', 'Producto removido de la reserva.');
    }

    private function autorizar(Request $request, Reserva $reserva): void
    {
        $usuario = $request->user();

        // Staff/Administrador: siempre pueden, el Staff solo si es su propia reserva
        if ($usuario && in_array($usuario->rol->nombre, ['Staff', 'Administrador'])) {
            if ($usuario->esStaff()) {
                $peluquero = $usuario->peluquero;
                abort_if(! $peluquero || $reserva->peluquero_id !== $peluquero->id, 403);
            }
            return;
        }

        // Reserva de un cliente con cuenta: solo el dueño
        if ($reserva->usuario_id) {
            abort_if(! $usuario || $usuario->id !== $reserva->usuario_id, 403);
            return;
        }

        // Reserva de invitado (sin usuario_id): accesible con el link, igual que reservas.show
    }
}