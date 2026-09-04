<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peluquero extends Model
{
    use HasFactory;

    protected $table = 'peluqueros';

    protected $fillable = ['usuario_id', 'especialidad', 'descripcion', 'estado'];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function horarios(): HasMany
    {
        return $this->hasMany(Horario::class, 'peluquero_id');
    }
    public function reservas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Reserva::class, 'peluquero_id');
    }
}