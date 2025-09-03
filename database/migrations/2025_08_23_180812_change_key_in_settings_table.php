<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('ALTER TABLE `settings` DROP INDEX `settings_key_unique`');
        DB::statement('ALTER TABLE `settings` MODIFY `key` TEXT');
        DB::statement('CREATE UNIQUE INDEX settings_key_unique ON `settings` (`key`(191))');
    }

    public function down(): void
    {
        // Revertir: volver a VARCHAR(191) (ajústalo si originalmente era distinto)
        DB::statement('ALTER TABLE `settings` MODIFY `key` VARCHAR(191)');
    }
};
