<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReclamacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // El número de documento depende del tipo
        $documento = match ($this->input('tipo_documento')) {
            'DNI'   => ['regex:/^[0-9]{8}$/'],
            'RUC'   => ['regex:/^(10|15|17|20)[0-9]{9}$/'],
            default => ['regex:/^[A-Za-z0-9]{6,12}$/'],
        };

        return [
            'nombre'            => ['required', 'string', 'min:3', 'max:150'],
            'tipo_documento'    => ['required', 'in:DNI,CE,Pasaporte,RUC'],
            'numero_documento'  => array_merge(['required'], $documento),
            'domicilio'         => ['required', 'string', 'min:5', 'max:200'],
            'telefono'          => ['required', 'regex:/^9[0-9]{8}$/'],
            'email'             => ['required', 'email', 'max:120'],
            'menor_edad'        => ['nullable', 'boolean'],
            'apoderado'         => ['nullable', 'required_if:menor_edad,1', 'string', 'max:150'],

            'tipo_bien'         => ['required', 'in:producto,servicio'],
            'numero_pedido'     => ['nullable', 'regex:/^[BF][0-9]{3}-[0-9]{8}$/'],
            'monto'             => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'descripcion_bien'  => ['required', 'string', 'min:3', 'max:255'],

            'tipo'              => ['required', 'in:reclamo,queja'],
            'detalle'           => ['required', 'string', 'min:10', 'max:2000'],
            'pedido_consumidor' => ['required', 'string', 'min:5', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'numero_documento.regex' => 'El número de documento no es válido para el tipo elegido (DNI: 8 dígitos, RUC: 11 dígitos).',
            'telefono.regex'         => 'El celular debe tener 9 dígitos y empezar con 9.',
            'apoderado.required_if'  => 'Si eres menor de edad, escribe el nombre de tu padre, madre o tutor.',
            'numero_pedido.regex'    => 'El número de pedido tiene la forma B001-00000010.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'            => 'nombre completo',
            'domicilio'         => 'domicilio',
            'descripcion_bien'  => 'descripción del producto o servicio',
            'detalle'           => 'detalle',
            'pedido_consumidor' => 'pedido',
            'tipo'              => 'tipo (reclamo o queja)',
            'tipo_bien'         => 'producto o servicio',
        ];
    }
}
