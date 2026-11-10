<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autore extends Model
{
    protected $table = 'autores';

    protected $perPage = 20;

    // Campos que se pueden guardar desde los formularios
    protected $fillable = [
        'nombres',
        'apellidos',
        'nacionalidad',
        'fecha_nacimiento',
    ];

    // Un autor tiene muchos libros
    public function productos()
    {
        return $this->hasMany(\App\Models\Producto::class, 'autor_id', 'id');
    }
}
