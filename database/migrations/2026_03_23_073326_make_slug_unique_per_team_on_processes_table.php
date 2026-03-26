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
        Schema::table('processes', function (Blueprint $table) {
            // 1) Eliminar la restricción UNIQUE existente sobre 'slug'
            $table->dropUnique(['slug']);
            // 2) Crear índice UNIQUE compuesto sobre ['team_id', 'slug']
            $table->unique(['team_id', 'slug'], 'processes_team_slug_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('processes', function (Blueprint $table) {
            // 1) Eliminar el índice UNIQUE compuesto
            $table->dropUnique('processes_team_slug_unique');
            // 2) Restaurar la restricción UNIQUE original sobre 'slug'
            $table->unique('slug');
        });
    }
};
