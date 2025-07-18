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
        Schema::table('products', function (Blueprint $table) {
            // Añade un índice simple a la columna 'name'
            $table->index('name', 'products_name_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Elimina el índice creado en up()
            $table->dropIndex('products_name_index');
            // Alternativamente, si no especificaste nombre:
            // $table->dropIndex(['name']);
        });
    }
};
