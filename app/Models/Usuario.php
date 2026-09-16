<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    // Tabla personalizada de la BD SuperFresco
    protected $table      = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps    = false;

    protected $fillable = [
        'id_rol',
        'nombres',
        'apellidos',
        'email',
        'password_hash',
        'telefono',
        'rol',
        'estado',
    ];

    protected $hidden = ['password_hash'];

    // La tabla 'usuarios' no tiene columna remember_token
    public $rememberTokenName = null;

    // Laravel usa 'password' internamente; lo mapeamos a password_hash
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    // Relación con la tabla roles
    public function rolRelacion()
    {
        return $this->belongsTo(Rol::class, 'id_rol', 'id_rol');
    }

    // Helper: ¿es administrador?
    public function esAdmin(): bool
    {
        return $this->rol === 'administrador';
    }
}
