<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $table = 'clientes';

    protected $perPage = 20;

    // Campos que se pueden guardar desde los formularios
    protected $fillable = [
        'user_id',
        'tipo_documento',
        'numero_documento',
        'nombres',
        'apellidos',
        'email',
        'telefono',
        'direccion',
    ];

    // Cuenta web del cliente (puede ser NULL: clientes atendidos en tienda)
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }

    // Un cliente tiene muchas compras
    public function ventas()
    {
        return $this->hasMany(\App\Models\Venta::class, 'cliente_id', 'id');
    }
}
