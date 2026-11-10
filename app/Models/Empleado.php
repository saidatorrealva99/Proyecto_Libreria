<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    protected $table = 'empleados';

    protected $perPage = 20;

    // Campos que se pueden guardar desde los formularios
    protected $fillable = [
        'dni',
        'nombres',
        'apellidos',
        'cargo',
        'email',
        'telefono',
        'sueldo',
        'fecha_contratacion',
        'estado',
    ];

    // Un empleado registra muchas ventas
    public function ventas()
    {
        return $this->hasMany(\App\Models\Venta::class, 'empleado_id', 'id');
    }
}
