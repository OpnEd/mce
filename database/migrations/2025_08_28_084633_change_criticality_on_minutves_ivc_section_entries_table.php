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
            $table->dropColumn('criticality');
        });

        DB::statement("ALTER TABLE `minutes_ivc_section_entries` 
            ADD COLUMN `criticality` ENUM('Crítico', 'Mayor', 'menor') 
            NOT NULL AFTER `entry_id`");
    }

    public function down()
    {
        Schema::table('minutes_ivc_section_entries', function (Blueprint $table) {
            $table->dropColumn('criticality');
        });

        DB::statement("ALTER TABLE `minutes_ivc_section_entries` 
            ADD COLUMN `criticality` ENUM('critical', 'major', 'minor') 
            NOT NULL AFTER `entry_id`");
    }

};
