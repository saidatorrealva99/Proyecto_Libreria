<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $perPage = 20;

    // Campos que se pueden guardar desde los formularios
    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    // Una categoría tiene muchos productos
    public function productos()
    {
        return $this->hasMany(\App\Models\Producto::class, 'categoria_id', 'id');
    }
}
