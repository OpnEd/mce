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
        // 1) Actualiza primero los valores existentes
        DB::table('purchases')
            ->where('status', 'in progress')
            ->update(['status' => 'in_progress']);

        // 2) En el closure de Schema::table, lanza el ALTER para cambiar el ENUM
        Schema::table('purchases', function (Blueprint $table) {
            DB::statement("
                ALTER TABLE `purchases`
                MODIFY COLUMN `status`
                ENUM('in_progress','confirmed','delivered')
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
