<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resena extends Model
{
    protected $table = 'resenas';

    protected $fillable = [
        'nombre',
        'calificacion',
        'comentario',
        'visible',
    ];
}
