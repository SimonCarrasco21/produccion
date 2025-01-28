<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fiado extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_cliente',
        'nombre_cliente',
        'productos',       // JSON con los productos
        'total_precio',    // Precio total del carrito
        'fecha_compra',    // Fecha de compra
        'user_id',         // Usuario que realiza el fiado
        'pagado',          // Indica si el carrito ya fue pagado
        'imagen_producto', // Ruta de la imagen del producto (opcional)
    ];

    /**
     * Relación con el usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con la tabla de productos (si decides usar una relación en lugar de JSON).
     */
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'fiado_producto')
            ->withPivot('cantidad', 'precio_total');
    }

    /**
     * Acceso rápido a la imagen del producto.
     * Si no guardas las imágenes directamente en la tabla `fiados`, utiliza esta relación.
     */
    public function imagenes()
    {
        return $this->hasManyThrough(ProductoImagen::class, Producto::class, 'id', 'producto_id', 'id', 'id');
    }
    
}
