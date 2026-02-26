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
        Schema::table('informes', function (Blueprint $table) {
            $table->dropUnique(['numero_informe']);
        });

        // Crear nuevo índice único compuesto
        Schema::table('informes', function (Blueprint $table) {
            $table->unique(['numero_informe', 'team_id'], 'informes_numero_informe_team_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('informes', function (Blueprint $table) {
            $table->dropUnique(['numero_informe', 'team_id']);
        });

        // Restaurar índice único original
        Schema::table('informes', function (Blueprint $table) {
            $table->unique('numero_informe');
        });
    }
};
