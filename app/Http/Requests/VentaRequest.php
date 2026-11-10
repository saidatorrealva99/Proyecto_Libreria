<?php

namespace App\Http\Requests;

use App\Models\Cliente;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class VentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Boleta: B001-00000001   Factura: F001-00000001
        $formato = $this->input('tipo_comprobante') === 'factura'
            ? '/^F[0-9]{3}-[0-9]{8}$/'
            : '/^B[0-9]{3}-[0-9]{8}$/';

        return [
            'tipo_comprobante'   => ['required', 'in:boleta,factura'],
            'numero_comprobante' => ['required', 'string', 'max:15', 'regex:' . $formato,
                                     Rule::unique('ventas', 'numero_comprobante')->ignore($this->route('venta'))],
            'cliente_id'         => ['required', 'integer', 'exists:clientes,id'],
            'empleado_id'        => ['required', 'integer', 'exists:empleados,id'],
            'fecha_venta'        => ['required', 'date', 'before_or_equal:now'],
            'subtotal'           => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'descuento'          => ['required', 'numeric', 'min:0', 'decimal:0,2', 'lte:subtotal'],
            'total'              => ['required', 'numeric', 'min:0', 'decimal:0,2'],
            'estado'             => ['required', 'in:pendiente,pagada,anulada'],
        ];
    }

    // Reglas que dependen de varios campos a la vez
    public function after(): array
    {
        return [
            function (Validator $validator) {
                // total = subtotal - descuento
                $esperado = round((float) $this->input('subtotal') - (float) $this->input('descuento'), 2);
                if (abs($esperado - (float) $this->input('total')) > 0.001) {
                    $validator->errors()->add('total', "El total debe ser subtotal - descuento = {$esperado}.");
                }

                // Una factura solo se emite a un cliente con RUC
                if ($this->input('tipo_comprobante') === 'factura') {
                    $cliente = Cliente::find($this->input('cliente_id'));
                    if ($cliente && $cliente->tipo_documento !== 'RUC') {
                        $validator->errors()->add('cliente_id', 'Para emitir factura el cliente debe tener RUC.');
                    }
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'numero_comprobante.regex' => 'Formato: B001-00000001 para boleta o F001-00000001 para factura.',
            'descuento.lte'            => 'El descuento no puede ser mayor que el subtotal.',
        ];
    }
}
