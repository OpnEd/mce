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
        Schema::table('anesthesia_sheets', function (Blueprint $table) {
            $table->string('recipe_number', 50)
                ->nullable()
                ->after('user_id')
                ->comment('Recipe number for the anesthesia sheet');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anesthesia_sheets', function (Blueprint $table) {
            //
        });
    }
};
