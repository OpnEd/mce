<?php

use App\Models\Quality\Records\Improvement\ChecklistItemAnswer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('improvement_plans', function (Blueprint $table) {
            $table->foreignIdFor(ChecklistItemAnswer::class)
                ->nullable()
                ->unique()
                ->after('team_id')
                ->constrained()
                ->nullOnDelete();

            $table->enum('status', [
                'pendiente', 
                'en_progreso_al_dia', 
                'en_progreso_con_retraso', 
                'en_verificacion', 
                'completado', 
                'cancelado'
            ])->default('pendiente')  // Agrega default si lo quieres
            ->change();
        });
    }

    public function down(): void
    {
        Schema::table('improvement_plans', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(ChecklistItemAnswer::class);
            $table->string('status')->change();
        });
    }
};
