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
        Schema::table('batches', function (Blueprint $table) {
            // 1) Eliminar la clave foránea existente
            $table->dropForeign(['manufacturer_id']);
            // 2) Hacer la columna nullable
            $table->unsignedBigInteger('manufacturer_id')->nullable()->change();
            // 3) Volver a agregar la foreign key con nullable
            $table->foreign('manufacturer_id')
                  ->references('id')
                  ->on('manufacturers')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // 1) Eliminar la clave foránea nullable
            $table->dropForeign(['manufacturer_id']);
            // 2) Hacer la columna NOT NULL
            $table->unsignedBigInteger('manufacturer_id')->nullable(false)->change();
            // 3) Volver a agregar la foreign key original
            $table->foreign('manufacturer_id')
                  ->references('id')
                  ->on('manufacturers')
                  ->onDelete('cascade');
        });
    }
};
