<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoImagenesTable extends Migration
{
    public function up()
    {
        Schema::create('producto_imagenes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id'); // Relación con la tabla productos
            $table->string('imagen'); // Ruta de la imagen
            $table->timestamps();

            // Llave foránea
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('producto_imagenes');
    }
}
