<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ---------- Ayudas para saber el rol ----------
    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function esPersonal(): bool
    {
        return in_array($this->rol, ['admin', 'empleado'], true);
    }

    public function esCliente(): bool
    {
        return $this->rol === 'cliente';
    }

    // Fichas de cliente de esta cuenta (una por documento: su DNI, el RUC de su empresa...)
    public function clientes()
    {
        return $this->hasMany(\App\Models\Cliente::class, 'user_id', 'id');
    }
}
