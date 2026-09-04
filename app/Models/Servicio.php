<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'servicios';

    protected $fillable = [
        'categoria_servicio_id', 'nombre', 'descripcion',
        'precio', 'duracion', 'imagen', 'estado',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaServicio::class, 'categoria_servicio_id');
    }
    public function reservas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Reserva::class, 'servicio_id');
    }
}