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
        Schema::table('anesthesia_sheet_items', function (Blueprint $table) {
            
            // Cambiamos a enum conservando NULL/NOT NULL y valor por defecto
            $table->enum('dose_measure_unit', [
                'mg',
                'mL',
                'tab',
                'UI',
            ])
            ->default('ml') // ajusta si quieres otro default
            ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anesthesia_sheet_items', function (Blueprint $table) {
            //
        });
    }
};
