<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('categorias')->insert([
            ['nombre' => 'Lácteos', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Granos', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Productos de Limpieza', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Galletas', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Bebidas', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Panadería', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Frutas y Verduras', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Embutidos', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Aseo Personal', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
