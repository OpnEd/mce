<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('areas_limpieza', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->string('nombre');
            $table->string('codigo')->nullable();
            $table->text('descripcion')->nullable();
            $table->enum('tipo', ['critica', 'semicritica', 'bajo_riesgo'])->default('semicritica');
            $table->enum('frecuencia', ['diaria', 'semanal', 'quincenal', 'mensual'])->default('diaria');
            $table->boolean('activa')->default(true);
            $table->timestamps();
            
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->index(['tenant_id', 'activa']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('areas_limpieza');
    }
};