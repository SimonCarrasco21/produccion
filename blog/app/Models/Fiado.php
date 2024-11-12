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
        'producto',
        'cantidad',
        'precio',
        'fecha_compra',
        'user_id' // Añadido para asociar el registro al usuario
    ];

    // Relación con el usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
