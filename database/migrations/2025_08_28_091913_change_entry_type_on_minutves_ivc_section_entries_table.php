<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up()
    {
        Schema::table('minutes_ivc_section_entries', function (Blueprint $table) {
            $table->dropColumn('entry_type');
        });

        DB::statement("ALTER TABLE `minutes_ivc_section_entries` 
            ADD COLUMN `entry_type` ENUM('text', 'boolean', 'file', 'select') 
            NOT NULL AFTER `entry_id`");
    }

    public function down()
    {
        Schema::table('minutes_ivc_section_entries', function (Blueprint $table) {
            $table->dropColumn('entry_type');
        });

        DB::statement("ALTER TABLE `minutes_ivc_section_entries` 
            ADD COLUMN `entry_type` ENUM('evidencia', 'informativo') 
            NOT NULL AFTER `entry_id`");
    }
};
