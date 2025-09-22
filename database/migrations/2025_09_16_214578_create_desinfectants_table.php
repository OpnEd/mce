<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
       /*  Schema::create('desinfectants', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Team::class)
                  ->constrained()
                  ->cascadeOnDelete();
            $table->string('name');
            $table->string('active_ingredient');
            $table->string('concentration');
            $table->text('indications')->nullable();
            $table->enum('level', ['alto', 'medio', 'bajo'])->default('medio');
            $table->json('applicable_areas')->nullable(); // IDs de áreas donde se puede usar
            $table->boolean('active')->default(true);
            $table->timestamps();
            
            $table->index(['team_id', 'active']);
        }); */
    }

    public function down()
    {
        /* Schema::dropIfExists('desinfectants'); */
    }
};