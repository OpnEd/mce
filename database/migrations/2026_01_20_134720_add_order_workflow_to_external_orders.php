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
        Schema::table('external_orders', function (Blueprint $table) {
            $table->string('otp_code', 4)->nullable();
            $table->timestamp('otp_generated_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->string('delivery_code_input', 4)->nullable();
        });

        // Tabla de intentos de OTP
        Schema::create('external_order_otp_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('external_order_id')->constrained('external_orders')->onDelete('cascade');
            $table->string('attempted_code', 4)->nullable();
            $table->boolean('success')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->index(['external_order_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_order_otp_attempts');
        Schema::table('external_orders', function (Blueprint $table) {
            $table->dropColumn(['otp_code', 'otp_generated_at', 'delivered_at', 'delivery_code_input']);
        });
    }
};
