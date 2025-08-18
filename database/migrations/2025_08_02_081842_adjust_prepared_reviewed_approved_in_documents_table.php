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
        Schema::table('documents', function (Blueprint $table) {
            // 1) Eliminamos las antiguas columnas de texto
            $table->dropColumn(['prepared', 'reviewed', 'approved']);
            
            // 2) Añadimos las nuevas FKs apuntando a users.id
            $table->foreignId('prepared_by')
                  ->nullable()
                  ->constrained('users')
                  ->cascadeOnDelete()
                  ->after('data');
            
            $table->foreignId('reviewed_by')
                  ->nullable()
                  ->constrained('users')
                  ->cascadeOnDelete()
                  ->after('prepared_by');
            
            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->cascadeOnDelete()
                  ->after('reviewed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // 1) Eliminamos las FKs nuevas
            $table->dropForeign(['prepared_by']);
            $table->dropColumn('prepared_by');

            $table->dropForeign(['reviewed_by']);
            $table->dropColumn('reviewed_by');

            $table->dropForeign(['approved_by']);
            $table->dropColumn('approved_by');

            // 2) Volvemos a crear las antiguas columnas de texto
            $table->string('prepared', 255)->nullable()->after('data');
            $table->string('reviewed', 255)->nullable()->after('prepared');
            $table->string('approved', 255)->nullable()->after('reviewed');
        });
    }
};
