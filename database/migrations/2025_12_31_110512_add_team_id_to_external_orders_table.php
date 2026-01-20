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
        Schema::table('external_orders', function (Blueprint $table) {            // Columna team_id nullable con referencia a teams
            $table->unsignedBigInteger('team_id')->nullable()->after('id');
            $table->foreign('team_id')
                  ->references('id')
                  ->on('teams')
                  ->nullOnDelete();

            // Columna status como enum
            $table->enum('status', ['pending', 'no_candidates', 'notified', 'assigned', 'delivered', 'rejected', 'cancelled'])
                  ->default('pending')
                  ->after('team_id');
            // Columna payload como JSON
            $table->json('payload')->nullable()->after('estimated_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('external_orders', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropColumn('team_id');
            $table->dropColumn('status');
        });
    }
};
