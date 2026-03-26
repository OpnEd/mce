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
        Schema::table('schedules', function (Blueprint $table) {
            $table->string('slug', 255)->nullable()->after('name');
            // 2) Crear índice UNIQUE compuesto sobre ['team_id', 'slug']
            $table->unique(['team_id', 'slug'], 'schedules_team_slug_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // 1) Eliminar el índice UNIQUE compuesto
            $table->dropUnique('schedules_team_slug_unique');
            $table->dropColumn('slug');
        });
    }
};
