<?php

use App\Models\SanitaryRegistry;
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
        Schema::table('batches', function (Blueprint $table) {
            // 1) Quitar la llave foránea y la columna relacionada
            $table->dropForeign(['sanitary_registry_id']);
            $table->dropColumn('sanitary_registry_id');

            // 2) Agregar el nuevo campo string
            $table->string('sanitary_registry', 75)->nullable();
            // (Reemplaza 'some_existing_column' por la columna tras la cual quieras ubicarlo)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('batches', function (Blueprint $table) {
            $table->foreignIdFor(SanitaryRegistry::class)
                ->after('manufacturer_id') // Ubicar después de manufacturer_id
                ->constrained()
                ->onDelete('cascade');
            $table->dropColumn('sanitary_registry_id');
        });
    }
};
