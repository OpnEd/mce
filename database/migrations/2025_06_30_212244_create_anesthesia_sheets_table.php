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
        Schema::create('anesthesia_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('surgeon_id')->constrained('users')->onDelete('cascade'); // Ensure the surgeon is a medical doctor
            $table->json('anamnesis')->nullable(); // Medical history
            $table->json('anesthesia_notes')->nullable(); // Notes related to anesthesia
            $table->timestamp('anesthesia_start_time')->nullable(); // Start time of anesthesia
            $table->timestamp('anesthesia_end_time')->nullable(); // End time of anesthesia
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anesthesia_sheets');
    }
};
