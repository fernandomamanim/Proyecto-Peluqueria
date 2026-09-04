<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reserva extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reservas';

    protected $fillable = [
        'usuario_id', 'peluquero_id', 'servicio_id',
        'fecha', 'hora_inicio', 'hora_fin', 'estado',
        'codigo_qr', 'fecha_expiracion', 'observaciones',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
            'fecha_expiracion' => 'datetime',
        ];
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function peluquero(): BelongsTo
    {
        return $this->belongsTo(Peluquero::class, 'peluquero_id');
    }

    public function servicio(): BelongsTo
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }

    public function pago(): HasOne
    {
        return $this->hasOne(Pago::class, 'reserva_id');
    }
}