<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaServicio extends Model
{
    use HasFactory;

    protected $table = 'categorias_servicios';

    protected $fillable = ['nombre', 'descripcion', 'estado'];

    public function servicios(): HasMany
    {
        return $this->hasMany(Servicio::class, 'categoria_servicio_id');
    }
}