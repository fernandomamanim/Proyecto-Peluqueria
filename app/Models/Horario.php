<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';

    protected $fillable = ['peluquero_id', 'dia_semana', 'hora_inicio', 'hora_fin', 'estado'];

    public function peluquero(): BelongsTo
    {
        return $this->belongsTo(Peluquero::class, 'peluquero_id');
    }
}