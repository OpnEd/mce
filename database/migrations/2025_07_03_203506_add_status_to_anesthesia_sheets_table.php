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
        Schema::table('anesthesia_sheets', function (Blueprint $table) {
            $table->enum('status', ['opened', 'closed'])
                ->default('opened')
                ->after('anesthesia_end_time')
                ->comment('Status of the anesthesia sheet: opened or closed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anesthesia_sheets', function (Blueprint $table) {
            //
        });
    }
};
