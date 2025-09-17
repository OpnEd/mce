<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('spill_cleanups', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');              // Fecha del derrame
            $table->time('hora')->nullable();   // Hora del derrame
            $table->string('ubicacion');        // Área o lugar
            $table->string('sustancia');        // Nombre del producto derramado
            $table->string('tipo')->nullable(); // Tipo (p.ej. medicamento, químico)
            $table->float('cantidad')->nullable();   // Cantidad derramada
            $table->string('unidad')->nullable();    // Unidad (ml, g, etc.)
            $table->text('medidas_seguridad')->nullable();  // Medidas de bioseguridad usadas
            $table->string('personal_expuesto')->nullable(); // Quien se expuso o lo descubrió
            $table->text('acciones')->nullable();    // Acciones o equipos usados
            $table->text('observaciones')->nullable(); // Comentarios adicionales

            // Para multi-tenant: referencia a la droguería o sucursal
            $table->foreignIdFor(Team::class)
                ->constrained()
                ->cascadeOnDelete();

            // Opcional: usuario que registró el derrame
            $table->foreignIdFor(User::class)->nullable()->constrained()->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('limpieza_derrame');
    }
};
