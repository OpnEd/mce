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
        Schema::create('external_order_items', function (Blueprint $table) {
            $table->id();
            // Clave foránea al ID interno de external_orders
            $table->foreignId('external_order_id')
                  ->constrained('external_orders')
                  ->cascadeOnDelete();
            // Atributos del ítem
            $table->string('sku');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('name');
            $table->integer('qty')->default(1);
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_order_items');
    }
};
