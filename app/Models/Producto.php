<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $table = 'productos';

    protected $perPage = 20;

    // Campos que se pueden guardar desde los formularios
    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'categoria_id',
        'autor_id',
        'isbn',
        'marca_editorial',
        'precio_venta',
        'stock',
        'stock_minimo',
        'estado',
    ];

    // Un producto pertenece a una categoría
    public function categoria()
    {
        return $this->belongsTo(\App\Models\Categoria::class, 'categoria_id', 'id');
    }

    // Un libro pertenece a un autor (NULL en útiles y papelería)
    public function autor()
    {
        return $this->belongsTo(\App\Models\Autore::class, 'autor_id', 'id');
    }

    // Mismo autor con el nombre que usa el generador (por si alguna vista lo usa)
    public function autore()
    {
        return $this->autor();
    }

    // Un producto aparece en muchos detalles de venta
    public function detalleVentas()
    {
        return $this->hasMany(\App\Models\DetalleVenta::class, 'producto_id', 'id');
    }
}
