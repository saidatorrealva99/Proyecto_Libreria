<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmpleadoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $empleado = $this->route('empleado');

        return [
            'dni'                => ['required', 'regex:/^[0-9]{8}$/',
                                     Rule::unique('empleados', 'dni')->ignore($empleado)],
            'nombres'            => ['required', 'string', 'max:100'],
            'apellidos'          => ['required', 'string', 'max:100'],
            'cargo'              => ['required', 'in:administrador,vendedor,cajero,almacenero'],
            'email'              => ['required', 'email', 'max:120',
                                     Rule::unique('empleados', 'email')->ignore($empleado)],
            'telefono'           => ['nullable', 'regex:/^9[0-9]{8}$/'],
            'sueldo'             => ['required', 'numeric', 'gt:0', 'max:99999999.99', 'decimal:0,2'],
            'fecha_contratacion' => ['required', 'date', 'before_or_equal:today'],
            'estado'             => ['required', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'dni.regex'      => 'El DNI debe tener exactamente 8 dígitos.',
            'telefono.regex' => 'El celular debe tener 9 dígitos y empezar con 9.',
        ];
    }
}
