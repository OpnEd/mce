<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            DB::statement("
                ALTER TABLE `purchases`
                MODIFY COLUMN `status`
                ENUM('in_progress','confirmed','delivered', 'received')
                NOT NULL
                DEFAULT 'in_progress'
            ");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            //
        });
    }
};
