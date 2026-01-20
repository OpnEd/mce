<?php

use App\Models\Api\ExternalOrder;
use App\Models\Team;
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
        Schema::create('external_order_team_candidates', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignIdFor(ExternalOrder::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Team::class)->constrained()->cascadeOnDelete();
            
            // Distancias: guardamos en metros y km para conveniencia
            $table->decimal('distance_km', 8, 3)->nullable()->comment('Distancia en Kilómetros');
            $table->integer('distance_m')->nullable()->index()->comment('Distancia en Metros');

            // Estado del candidato para este pedido
            $table->string('status')->default('notified')->index(); // Ej: notified, accepted, rejected

            $table->timestamp('notified_at')->nullable()->index();
            $table->timestamp('accepted_at')->nullable()->index();
            $table->timestamp('declined_at')->nullable()->index();
            
            $table->timestamps();

            // Evitar duplicados de candidatos para una misma orden
            $table->unique(['external_order_id', 'team_id'], 'unique_order_team_candidate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_order_team_candidates');
    }
};
