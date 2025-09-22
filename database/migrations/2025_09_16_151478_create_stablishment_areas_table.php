<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stablishment_areas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Team::class)
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['critica', 'semicritica', 'bajo_riesgo'])->default('semicritica');
            $table->enum('frequency', ['diaria', 'semanal', 'quincenal', 'mensual'])->default('diaria');
            $table->boolean('active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['team_id', 'active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('stablishment_areas');
    }
};