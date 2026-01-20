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
        Schema::create('external_orders', function (Blueprint $table) {
            $table->id();
            $table->string('external_order_id')->unique();   // ID único de la orden externa
            $table->timestamp('external_created_at')->nullable(); // Fecha UTC enviada en payload
            // Datos del cliente
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->string('customer_address');
            $table->decimal('customer_lat', 10, 7)->nullable();
            $table->decimal('customer_lng', 10, 7)->nullable();
            // Campos de metadata
            $table->integer('notify_radius_m')->nullable();
            $table->text('notes')->nullable();
            $table->string('payment_method')->nullable();
            $table->integer('estimated_total')->nullable();
            // Payload completo almacenado como JSON
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_orders');
    }
};
