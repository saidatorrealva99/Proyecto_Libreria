<?php

namespace App\Http\Requests;

use App\Models\Producto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class DetalleVentaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $detalle = $this->route('detalle_venta') ?? $this->route('detalleVenta');

        return [
            'venta_id'        => ['required', 'integer', 'exists:ventas,id'],
            // Un mismo producto no se repite dentro de la misma venta
            'producto_id'     => ['required', 'integer', 'exists:productos,id',
                                  Rule::unique('detalle_ventas', 'producto_id')
                                      ->where('venta_id', $this->input('venta_id'))
                                      ->ignore($detalle)],
            'cantidad'        => ['required', 'integer', 'min:1'],
            'precio_unitario' => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'subtotal'        => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                // subtotal = cantidad x precio_unitario
                $esperado = round((int) $this->input('cantidad') * (float) $this->input('precio_unitario'), 2);
                if (abs($esperado - (float) $this->input('subtotal')) > 0.001) {
                    $validator->errors()->add('subtotal', "El subtotal debe ser cantidad x precio = {$esperado}.");
                }

                // No vender más de lo que hay en stock (solo al registrar)
                if ($this->isMethod('post')) {
                    $producto = Producto::find($this->input('producto_id'));
                    if ($producto && (int) $this->input('cantidad') > $producto->stock) {
                        $validator->errors()->add('cantidad', "Stock insuficiente: solo hay {$producto->stock} unidades.");
                    }
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'producto_id.unique' => 'Ese producto ya está en esta venta; modifica la cantidad.',
        ];
    }
}
