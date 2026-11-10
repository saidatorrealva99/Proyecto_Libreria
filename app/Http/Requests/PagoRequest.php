<?php

namespace App\Http\Requests;

use App\Models\Pago;
use App\Models\Venta;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class PagoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'venta_id'         => ['required', 'integer', 'exists:ventas,id'],
            'metodo_pago'      => ['required', 'in:efectivo,tarjeta,yape,plin,transferencia'],
            'monto'            => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'numero_operacion' => ['nullable', 'required_unless:metodo_pago,efectivo', 'string', 'max:30'],
            'fecha_pago'       => ['required', 'date', 'before_or_equal:now'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $venta = Venta::find($this->input('venta_id'));
                if (! $venta) {
                    return;
                }

                // La suma de pagos no puede pasar el total de la venta
                $pago     = $this->route('pago');
                $pagoId   = $pago instanceof Pago ? $pago->id : $pago;
                $yaPagado = Pago::where('venta_id', $venta->id)
                                ->when($pagoId, fn ($q) => $q->where('id', '!=', $pagoId))
                                ->sum('monto');
                $saldo    = round($venta->total - $yaPagado, 2);

                if ((float) $this->input('monto') > $saldo + 0.001) {
                    $validator->errors()->add('monto', "El monto supera el saldo pendiente de la venta (S/ {$saldo}).");
                }

                if ($venta->estado === 'anulada') {
                    $validator->errors()->add('venta_id', 'No se puede pagar una venta anulada.');
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'numero_operacion.required_unless' => 'El número de operación es obligatorio si el pago no es en efectivo.',
        ];
    }
}
