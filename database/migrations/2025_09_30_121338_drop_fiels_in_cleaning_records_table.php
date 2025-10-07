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
        Schema::table('cleaning_records', function (Blueprint $table) {
            $table->dropColumn('substances_used');
            $table->dropColumn('implements_used');
            $table->dropColumn('status');
            $table->dropColumn('search_evidence_pests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cleaning_records', function (Blueprint $table) {
            $table->json('substances_used')->nullable();
            $table->json('implements_used')->nullable();
            $table->enum('status', ['en_proceso', 'completada'])->default('completada');
            $table->boolean('search_evidence_pests')->default(false);
        });
    }
};
