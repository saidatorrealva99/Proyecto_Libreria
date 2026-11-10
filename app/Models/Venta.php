<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $table = 'ventas';

    protected $perPage = 20;

    // Campos que se pueden guardar desde los formularios
    protected $fillable = [
        'tipo_comprobante',
        'numero_comprobante',
        'cliente_id',
        'empleado_id',
        'fecha_venta',
        'subtotal',
        'descuento',
        'total',
        'estado',
    ];

    protected $casts = [
        'fecha_venta' => 'datetime',
    ];

    public function cliente()
    {
        return $this->belongsTo(\App\Models\Cliente::class, 'cliente_id', 'id');
    }

    public function empleado()
    {
        return $this->belongsTo(\App\Models\Empleado::class, 'empleado_id', 'id');
    }

    // Productos vendidos en esta venta
    public function detalles()
    {
        return $this->hasMany(\App\Models\DetalleVenta::class, 'venta_id', 'id');
    }

    // Mismo detalle con el nombre que usa el generador
    public function detalleVentas()
    {
        return $this->detalles();
    }

    // Pagos de esta venta
    public function pagos()
    {
        return $this->hasMany(\App\Models\Pago::class, 'venta_id', 'id');
    }
}
