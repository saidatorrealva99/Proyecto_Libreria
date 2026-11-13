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
        'imagen',
        'categoria_id',
        'autor_id',
        'isbn',
        'marca_editorial',
        'precio_venta',
        'stock',
        'stock_minimo',
        'estado',
    ];

    // URL pública de la foto (o null si no tiene)
    public function getUrlImagenAttribute(): ?string
    {
        return $this->imagen ? asset('storage/' . $this->imagen) : null;
    }

    // Nombre con el que se guarda la foto: código + nombre, ej. lib-0001-cien-anos-de-soledad
    public static function nombreArchivo(string $codigo, string $nombre): string
    {
        return \Illuminate\Support\Str::slug($codigo . ' ' . $nombre);
    }

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
