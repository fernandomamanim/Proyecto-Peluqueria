<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'reserva_id', 'monto', 'descuento', 'metodo_pago',
        'comprobante', 'estado', 'fecha_pago', 'observaciones',
    ];

    protected function casts(): array
    {
        return ['fecha_pago' => 'datetime'];
    }

    public function reserva(): BelongsTo
    {
        return $this->belongsTo(Reserva::class, 'reserva_id');
    }
}