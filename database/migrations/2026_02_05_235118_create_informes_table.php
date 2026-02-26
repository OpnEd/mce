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
        Schema::create('informes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_informe')->unique();
            $table->year('anio');
            $table->text('descripcion')->nullable();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // Totales de residuos generados en el año
            $table->float('total_reciclable', 10, 2)->default(0);
            $table->float('total_ordinario', 10, 2)->default(0);
            $table->float('total_guardian', 10, 2)->default(0);
            $table->float('total_bolsa_roja', 10, 2)->default(0);
            $table->float('total_general', 10, 2)->default(0);
            
            // Datos del informe
            $table->integer('cantidad_registros')->default(0);
            $table->dateTime('fecha_generacion')->useCurrent();
            $table->string('estado')->default('generado'); // generado, validado, archivado
            $table->json('resumen')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Índices para búsquedas frecuentes
            $table->index('anio');
            $table->index('team_id');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informes');
    }
};
