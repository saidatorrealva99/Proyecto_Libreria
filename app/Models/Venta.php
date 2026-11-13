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
        'canal',              // tienda | web
        'entrega',            // presencial | recojo | delivery
        'direccion_envio',
        'referencia_envio',
        'telefono_contacto',
        'costo_envio',
        'estado_pedido',      // por_confirmar | confirmado | listo_recojo | en_camino | entregado | anulado
        'observaciones',
    ];

    protected $casts = [
        'fecha_venta' => 'datetime',
    ];

    // Textos y colores para mostrar el estado del pedido web
    public const ESTADOS_PEDIDO = [
        'por_confirmar' => ['Por confirmar pago', 'warning', 'fa-hourglass-half'],
        'confirmado'    => ['Pago confirmado',    'primary', 'fa-circle-check'],
        'listo_recojo'  => ['Listo para recoger', 'info',    'fa-store'],
        'en_camino'     => ['En camino',          'info',    'fa-truck-fast'],
        'entregado'     => ['Entregado',          'success', 'fa-box-open'],
        'anulado'       => ['Anulado',            'danger',  'fa-ban'],
    ];

    // Fecha límite para confirmar el pago de un pedido web (después se anula solo)
    public function getVenceEnAttribute(): ?\Illuminate\Support\Carbon
    {
        if ($this->canal !== 'web' || $this->estado_pedido !== 'por_confirmar' || ! $this->fecha_venta) {
            return null;
        }

        return $this->fecha_venta->copy()->addHours((int) config('libreria.horas_para_pagar', 24));
    }

    // Lo que ya se pagó y lo que falta pagar
    public function getPagadoAttribute(): float
    {
        return round((float) $this->pagos->sum('monto'), 2);
    }

    public function getSaldoAttribute(): float
    {
        return round(max(0, (float) $this->total - $this->pagado), 2);
    }

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
