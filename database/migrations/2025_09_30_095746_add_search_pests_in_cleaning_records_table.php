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
            $table->boolean('search_evidence_pests')->default(false)->after('implements_used');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cleaning_records', function (Blueprint $table) {
            $table->dropColumn('search_evidence_pests');
        });
    }
};
