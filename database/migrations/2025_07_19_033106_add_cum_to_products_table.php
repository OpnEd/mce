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
        Schema::table('products', function (Blueprint $table) {
            //$table->string('expediente', 15);
            //$table->string('titular', 255);
            //$table->string('registro_sanitario', 75);
            //$table->date('fecha_expedicion');
            //$table->date('fecha_vencimiento');
            //$table->enum('estado_registro', ['vigente', 'vencido']);
            //$table->smallInteger('consecutivo');
            //$table->smallInteger('cantidad_cum');
            //$table->boolean('estado_cum');
            //$table->boolean('muestra_medica');
            //$table->string('unidad', 5);
            //$table->string('atc', 75);
            //$table->string('descripcion_atc', 75);
            //$table->enum('via_administracion', ['oral', 'iv', 'im']);
            //$table->string('concentracion', 1);
            //$table->string('unidad_medida_pa', 5);
            //$table->decimal('cantidad', 6, 2);
            //$table->string('unidad_referencia', 75);
            //$table->string('forma_farmaceutica', 75);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Recreate the column and foreign key
            $table->foreignIdFor(
                \App\Models\PharmaceuticalForm::class
            )
            ->nullable()
            ->constrained()
            ->onDelete('set null');
        });
    }
};
