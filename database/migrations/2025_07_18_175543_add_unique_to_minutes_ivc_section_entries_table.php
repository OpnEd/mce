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
        Schema::table('minutes_ivc_section_entries', function (Blueprint $table) {
            // 1) Elimina la restricción UNIQUE existente sobre entry_id
            $table->dropUnique(['entry_id']);
            // 2) Crea un índice UNIQUE combinado sobre team_id + entry_id
            $table->unique(['minutes_ivc_section_id', 'entry_id'], 'mivc_entries_team_entry_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('minutes_ivc_section_entries', function (Blueprint $table) {
            // 1) Elimina el índice UNIQUE combinado
            $table->dropUnique('mivc_entries_team_entry_unique');
            // 2) Restaura la restricción UNIQUE original sobre entry_id
            $table->unique('entry_id');
        });
    }
};
