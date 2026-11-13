<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompraRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Para factura el documento siempre es RUC
     */
    protected function prepareForValidation(): void
    {
        if ($this->input('comprobante') === 'factura') {
            $this->merge(['tipo_documento' => 'RUC']);
        }
        $this->merge([
            'numero_documento' => preg_replace('/\s+/', '', (string) $this->input('numero_documento')),
            'telefono'         => preg_replace('/\s+/', '', (string) $this->input('telefono')),
            'numero_operacion' => trim((string) $this->input('numero_operacion')),
        ]);
    }

    public function rules(): array
    {
        $formato = match ($this->input('tipo_documento')) {
            'RUC'   => '/^(10|15|17|20)[0-9]{9}$/',
            'CE'    => '/^[0-9]{9,12}$/',
            default => '/^[0-9]{8}$/',
        };

        return [
            // 1. Entrega
            'entrega'          => ['required', 'in:recojo,delivery'],
            'direccion'        => ['required_if:entrega,delivery', 'nullable', 'string', 'max:200'],
            'referencia'       => ['nullable', 'string', 'max:200'],
            'telefono'         => ['required', 'regex:/^9[0-9]{8}$/'],

            // 2. Comprobante y datos
            'comprobante'      => ['required', 'in:boleta,factura'],
            'tipo_documento'   => ['required', 'in:DNI,CE,RUC'],
            'numero_documento' => ['required', 'regex:' . $formato],
            'nombres'          => ['required', 'string', 'max:100'],
            'apellidos'        => ['required_if:comprobante,boleta', 'nullable', 'string', 'max:100'],

            // 3. Pago
            'metodo_pago'      => ['required', 'in:yape,plin,transferencia'],
            'numero_operacion' => ['required', 'string', 'min:4', 'max:30'],
            'acepto'           => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'entrega.required'           => 'Elige cómo quieres recibir tu pedido.',
            'direccion.required_if'      => 'Escribe la dirección de entrega.',
            'telefono.required'          => 'Escribe un celular de contacto.',
            'telefono.regex'             => 'El celular debe tener 9 dígitos y empezar con 9.',
            'comprobante.required'       => 'Elige boleta o factura.',
            'numero_documento.required'  => 'Escribe tu número de documento.',
            'numero_documento.regex'     => 'Número inválido: DNI = 8 dígitos, CE = 9 a 12 dígitos, RUC = 11 dígitos (10, 15, 17 o 20…).',
            'nombres.required'           => 'Escribe tus nombres (o la razón social, si es factura).',
            'apellidos.required_if'      => 'Escribe tus apellidos.',
            'metodo_pago.required'       => 'Elige cómo vas a pagar.',
            'numero_operacion.required'  => 'Escribe el número de operación de tu pago.',
            'numero_operacion.min'       => 'El número de operación parece incompleto.',
            'acepto.accepted'            => 'Debes aceptar las condiciones de compra.',
        ];
    }
}
