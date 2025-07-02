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
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade'); // Ensure the pet is a valid record
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
