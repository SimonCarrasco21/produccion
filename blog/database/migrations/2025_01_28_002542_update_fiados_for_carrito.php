<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('fiados', function (Blueprint $table) {
        $table->boolean('pagado')->nullable()->after('user_id'); // Indica si fue pagado
        $table->string('imagen_producto')->nullable()->after('productos'); // Ruta de la imagen del producto
    });
}

public function down()
{
    Schema::table('fiados', function (Blueprint $table) {
        $table->dropColumn('pagado');
        $table->dropColumn('imagen_producto');
    });
}

};
