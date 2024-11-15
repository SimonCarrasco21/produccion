<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'categoria_id',  // Relación con la tabla de categorías
        'fecha_vencimiento', // Fecha de vencimiento
        'user_id' // Relación con el usuario
    ];

    // Relación con la categoría
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con los fiados (opcional, para futuras mejoras)
    // Si decides implementar una tabla intermedia
    public function fiados()
    {
        return $this->belongsToMany(Fiado::class, 'fiado_producto')->withPivot('cantidad', 'precio_total');
    }

    // Método para reducir el stock del producto
    public function reducirStock($cantidad)
    {
        if ($this->stock < $cantidad) {
            throw new \Exception("Stock insuficiente para el producto: {$this->nombre}");
        }

        $this->stock -= $cantidad;
        $this->save();
    }

    // Método para aumentar el stock del producto (opcional)
    public function aumentarStock($cantidad)
    {
        $this->stock += $cantidad;
        $this->save();
    }
}
