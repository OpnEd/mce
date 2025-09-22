<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cleaning_records', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Team::class)
                  ->constrained()
                  ->cascadeOnDelete();
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->json('cleaned_areas'); // Array con IDs de áreas y si fueron sanitizadas
            $table->json('substances_used'); // Array con ID sustancia y cantidad
            $table->json('implements_used'); // Array con IDs de implementos
            $table->text('observations')->nullable();
            $table->string('reviewed_by')->nullable(); // Usuario que supervisó
            $table->enum('status', ['en_proceso', 'completada'])->default('completada');
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['team_id', 'created_at']);
            $table->index(['team_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cleaning_records');
    }
};