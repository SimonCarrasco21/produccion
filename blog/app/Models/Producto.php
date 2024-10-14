<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    // Definir los campos que pueden ser llenados con asignación masiva
    protected $fillable = ['nombre', 'descripcion', 'precio', 'stock'];
}