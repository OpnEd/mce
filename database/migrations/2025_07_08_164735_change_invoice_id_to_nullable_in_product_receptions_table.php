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
        Schema::table('product_receptions', function (Blueprint $table) {
            // Elimina la foreign key si existe
            $table->dropForeign(['invoice_id']);

            // Haz la columna nullable
            $table->foreignId('invoice_id')->nullable()->change();

            // Vuelve a agregar la foreign key con la nueva acción
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_reception', function (Blueprint $table) {
            //
        });
    }
};
