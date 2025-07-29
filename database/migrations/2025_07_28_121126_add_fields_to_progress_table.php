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
        Schema::table('progress', function (Blueprint $table) {
            $table->foreignId('enrollment_id')->nullable()->constrained('enrollments')->onDelete('cascade')->after('id');
            $table->foreignId('lesson_id')->nullable()->constrained('lessons')->onDelete('cascade');
            $table->string('status', 15)->default('not_started'); // Estados: not_started, in_progress, completed
            $table->date('started_at')->nullable();
            $table->date('completed_at')->nullable();
            $table->date('last_accessed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('progress', function (Blueprint $table) {
            $table->dropForeign(['enrollment_id']);
            $table->dropColumn('enrollment_id');
            $table->dropForeign(['lesson_id']);
            $table->dropColumn('lesson_id');
            $table->dropColumn('status');
            $table->dropColumn('started_at');
            $table->dropColumn('completed_at');
            $table->dropColumn('last_accessed_at');
        });
    }
};
