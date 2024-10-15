<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('productos', function (Blueprint $table) {
        $table->unsignedBigInteger('categoria_id')->after('stock'); // Relación con la tabla de categorías
        $table->date('fecha_vencimiento')->nullable()->after('categoria_id'); // Campo para la fecha de vencimiento
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('productos', function (Blueprint $table) {
        $table->dropColumn('categoria_id');
        $table->dropColumn('fecha_vencimiento');
    });
}
};
