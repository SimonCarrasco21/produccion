<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoImagen extends Model
{
    use HasFactory;

    protected $table = 'producto_imagenes'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'producto_id', // Relación con la tabla productos
        'imagen',      // Ruta de la imagen
    ];

    /**
     * Relación con el modelo Producto.
     */
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
