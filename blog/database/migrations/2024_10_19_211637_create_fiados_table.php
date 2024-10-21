<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiadosTable extends Migration
{
    public function up()
    {
        Schema::create('fiados', function (Blueprint $table) {
            $table->id(); // ID único del registro
            $table->unsignedBigInteger('id_cliente'); // ID del cliente (sin relación foránea por ahora)
            $table->string('nombre_cliente'); // Nombre del cliente
            $table->string('producto'); // Nombre del producto fiado
            $table->integer('cantidad'); // Cantidad de productos fiados
            $table->decimal('precio', 10, 2); // Precio total del fiado
            $table->date('fecha_compra'); // Fecha de compra
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('fiados');
    }
}
