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
        'productos',
        'total_precio',
        'fecha_compra',
        'user_id'
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación con los productos (opcional, para futuras mejoras)
    // Si decides cambiar a una tabla intermedia en lugar de usar JSON
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'fiado_producto')->withPivot('cantidad', 'precio_total');
    }
}
