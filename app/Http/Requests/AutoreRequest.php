<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AutoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombres'          => ['required', 'string', 'max:100'],
            'apellidos'        => ['required', 'string', 'max:100'],
            'nacionalidad'     => ['nullable', 'string', 'max:60'],
            'fecha_nacimiento' => ['nullable', 'date', 'before:today'],
        ];
    }
}
