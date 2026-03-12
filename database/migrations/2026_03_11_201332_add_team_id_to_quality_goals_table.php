<?php

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
        Schema::table('quality_goals', function (Blueprint $table) {
            $table->foreignIdFor(Team::class)
                ->nullable()
                ->index()
                ->constrained()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quality_goals', function (Blueprint $table) {
            $table->dropConstrainedForeignIdFor(Team::class);
        });
    }
};
