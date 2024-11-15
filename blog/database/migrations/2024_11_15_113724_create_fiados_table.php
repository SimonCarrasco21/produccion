<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFiadosTable extends Migration
{
    public function up()
    {
        Schema::create('fiados', function (Blueprint $table) {
            $table->id();
            $table->string('id_cliente');
            $table->string('nombre_cliente');
            $table->json('productos'); // Almacena múltiples productos como JSON
            $table->decimal('total_precio', 10, 2);
            $table->dateTime('fecha_compra')->default(now());
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fiados');
    }
}
