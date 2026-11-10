<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClienteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $cliente = $this->route('cliente');
        $tipo    = $this->input('tipo_documento');

        // El formato del número depende del tipo de documento
        $formato = match ($tipo) {
            'RUC'   => '/^(10|15|17|20)[0-9]{9}$/',
            'CE'    => '/^[0-9]{9,12}$/',
            default => '/^[0-9]{8}$/',          // DNI
        };

        return [
            'tipo_documento'   => ['required', 'in:DNI,RUC,CE'],
            'numero_documento' => ['required', 'string', 'max:12', 'regex:' . $formato,
                                   Rule::unique('clientes', 'numero_documento')
                                       ->where('tipo_documento', $tipo)
                                       ->ignore($cliente)],
            'nombres'          => ['required', 'string', 'max:100'],
            'apellidos'        => ['nullable', 'required_unless:tipo_documento,RUC', 'string', 'max:100'],
            'email'            => ['nullable', 'email', 'max:120',
                                   Rule::unique('clientes', 'email')->ignore($cliente)],
            'telefono'         => ['nullable', 'regex:/^[0-9]{7,9}$/'],
            'direccion'        => ['nullable', 'string', 'max:200'],
        ];
    }

    public function messages(): array
    {
        return [
            'numero_documento.regex'    => 'Número inválido: DNI = 8 dígitos, RUC = 11 dígitos (10/15/17/20...), CE = 9 a 12 dígitos.',
            'numero_documento.unique'   => 'Ya existe un cliente con ese documento.',
            'apellidos.required_unless' => 'Los apellidos son obligatorios (salvo para RUC).',
            'telefono.regex'            => 'El teléfono debe tener entre 7 y 9 dígitos.',
        ];
    }
}
