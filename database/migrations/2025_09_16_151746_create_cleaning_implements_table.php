<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cleaning_implements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Team::class)
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['desechable', 'reutilizable'])->default('reutilizable');
            $table->json('areas_use')->nullable(); // IDs de áreas donde se usa
            $table->boolean('active')->default(true);
            $table->softDeletes();
            $table->timestamps();
            
            $table->index(['team_id', 'active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('cleaning_implements');
    }
};