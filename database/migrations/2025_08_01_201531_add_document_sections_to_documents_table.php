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
            $table->dropColumn(['body']);
            $table->text('objective')->nullable();
            $table->text('scope')->nullable();
            $table->json('references')->nullable();;
            $table->json('terms')->nullable();
            $table->json('responsibilities')->nullable();;
            $table->json('procedure')->nullable();
            $table->json('annexes')->nullable();
            $table->string('prepared', 255)->nullable();
            $table->string('reviewed', 255)->nullable();
            $table->string('approved', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // 1) Volver a agregar la columna 'body' como text nullable
            $table->text('body')->nullable()->after('document_category_id');

            // 2) Eliminar las columnas recién creadas
            $table->dropColumn([
                'objective',
                'scope',
                'references',
                'terms',
                'responsibilities',
                'procedure',
                'annexes',
            ]);
        });
    }
};
