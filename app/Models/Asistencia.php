<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asistencia extends Model
{
    protected $table = 'asistencias';

    protected $fillable = ['peluquero_id', 'fecha', 'hora_entrada', 'hora_salida'];

    protected function casts(): array
    {
        return ['fecha' => 'date'];
    }

    public function peluquero(): BelongsTo
    {
        return $this->belongsTo(Peluquero::class, 'peluquero_id');
    }
}