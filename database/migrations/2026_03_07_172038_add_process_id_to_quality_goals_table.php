<?php

use App\Models\Process;
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
        /* Schema::table('quality_goals', function (Blueprint $table) {
            $table->foreignIdFor(Process::class)->nullable();
        }); */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /* Schema::table('quality_goals', function (Blueprint $table) {
            $table->dropForeignFor(Process::class);
        }); */
    }
};
