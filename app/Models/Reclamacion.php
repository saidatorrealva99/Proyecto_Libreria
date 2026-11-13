<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Reclamacion extends Model
{
    protected $table = 'reclamaciones';

    // Plazo legal para responder (días hábiles, improrrogable)
    public const DIAS_HABILES = 15;

    protected $fillable = [
        'codigo', 'user_id', 'nombre', 'tipo_documento', 'numero_documento', 'domicilio', 'telefono', 'email',
        'menor_edad', 'apoderado', 'tipo_bien', 'numero_pedido', 'monto', 'descripcion_bien',
        'tipo', 'detalle', 'pedido_consumidor', 'estado', 'respuesta', 'respondido_por', 'respondido_at',
    ];

    protected $casts = [
        'menor_edad'    => 'boolean',
        'respondido_at' => 'datetime',
    ];

    // Fecha límite para responder: 15 días hábiles (lunes a viernes) desde el día siguiente
    // Nota: no descuenta feriados; revísalos en el calendario.
    public function getFechaLimiteAttribute(): Carbon
    {
        return $this->created_at->copy()->startOfDay()->addWeekdays(self::DIAS_HABILES)->endOfDay();
    }

    // Días hábiles que quedan (negativo = vencido)
    public function getDiasRestantesAttribute(): int
    {
        $hoy = now()->startOfDay();
        $limite = $this->fecha_limite->copy()->startOfDay();

        return $hoy->lte($limite)
            ? (int) $hoy->diffInWeekdays($limite)
            : -(int) $limite->diffInWeekdays($hoy);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responsable()
    {
        return $this->belongsTo(User::class, 'respondido_por');
    }

    /**
     * Siguiente número correlativo del año: 2026-000001
     * (llamar dentro de una transacción)
     */
    public static function siguienteCodigo(): string
    {
        $anio = now()->format('Y');

        $ultimo = static::where('codigo', 'like', $anio . '-%')
            ->lockForUpdate()
            ->orderByDesc('codigo')
            ->value('codigo');

        $numero = $ultimo ? ((int) substr($ultimo, 5)) + 1 : 1;

        return sprintf('%s-%06d', $anio, $numero);
    }
}
