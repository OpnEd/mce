<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('cleaning_records', function (Blueprint $table) {
            $table->enum('shift', ['mañana', 'tarde', 'dia_completo'])
                  ->default('dia_completo')
                  ->after('end_time');
            
            $table->string('shift_notes')->nullable()->after('shift');
        });
    }

    public function down()
    {
        Schema::table('cleaning_records', function (Blueprint $table) {
            $table->dropColumn(['shift', 'shift_notes']);
        });
    }
};