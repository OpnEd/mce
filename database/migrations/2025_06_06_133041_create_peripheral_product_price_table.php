<?php

use App\Models\Product;
use App\Models\Team;
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
        Schema::create('peripheral_product_price', function (Blueprint $table) {
            $table->id();
        $table->foreignIdFor(Team::class)
            ->constrained()
            ->cascadeOnDelete();
        $table->foreignIdFor(Product::class)
            ->constrained()
            ->cascadeOnDelete();
        $table->unsignedInteger('min_stock'); //stock mínimo
        $table->decimal('sale_price', 10, 2);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peripheral_product_price');
    }
};
