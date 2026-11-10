<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $perPage = 20;

    // Campos que se pueden guardar desde los formularios
    protected $fillable = [
        'venta_id',
        'metodo_pago',
        'monto',
        'numero_operacion',
        'fecha_pago',
    ];

    protected $casts = [
        'fecha_pago' => 'datetime',
    ];

    public function venta()
    {
        return $this->belongsTo(\App\Models\Venta::class, 'venta_id', 'id');
    }
}
