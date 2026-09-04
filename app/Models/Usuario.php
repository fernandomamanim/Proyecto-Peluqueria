<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'rol_id',
        'nombre',
        'primer_apellido',
        'segundo_apellido',
        'correo',
        'telefono',
        'password',
        'estado',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    // Laravel usa "email" internamente para auth; mapeamos a "correo"
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function username()
    {
        return 'correo';
    }

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'rol_id');
    }

    public function esAdministrador(): bool
    {
        return $this->rol->nombre === 'Administrador';
    }

    public function esStaff(): bool
    {
        return $this->rol->nombre === 'Staff';
    }

    public function esCliente(): bool
    {
        return $this->rol->nombre === 'Cliente';
    }
    public function reservas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Reserva::class, 'usuario_id');
    }
    public function peluquero(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Peluquero::class, 'usuario_id');
    }
}