<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $producto = $this->route('producto');

        return [
            'codigo'          => ['required', 'string', 'max:20', 'regex:/^[A-Z]{3}-[0-9]{4}$/',
                                  Rule::unique('productos', 'codigo')->ignore($producto)],
            'nombre'          => ['required', 'string', 'max:150'],
            'descripcion'     => ['nullable', 'string', 'max:255'],
            'categoria_id'    => ['required', 'integer', 'exists:categorias,id'],
            'autor_id'        => ['nullable', 'integer', 'exists:autores,id'],
            'isbn'            => ['nullable', 'string', 'max:17', 'regex:/^97[89][0-9-]{10,14}$/',
                                  Rule::unique('productos', 'isbn')->ignore($producto)],
            'marca_editorial' => ['nullable', 'string', 'max:100'],
            'precio_venta'    => ['required', 'numeric', 'gt:0', 'max:99999999.99', 'decimal:0,2'],
            'stock'           => ['required', 'integer', 'min:0'],
            'stock_minimo'    => ['required', 'integer', 'min:0'],
            'estado'          => ['required', 'in:0,1'],
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.regex' => 'El código debe tener el formato AAA-0000 (ej: LIB-0001).',
            'isbn.regex'   => 'El ISBN debe empezar con 978 o 979 (ej: 978-6124215037).',
        ];
    }
}
