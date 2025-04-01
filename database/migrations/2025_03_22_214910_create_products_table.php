<?php

use App\Models\PharmaceuticalForm;
use App\Models\ProductCategory;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ProductCategory::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(PharmaceuticalForm::class)->nullable()->constrained()->onDelete('set null');
            $table->string('code');
            $table->string('name');
            $table->string('drug')->nullable(); // Nombre del principio activo
            $table->string('description'); // Presentación comercial
            $table->boolean('fractionable')->default(false);
            $table->decimal('conversion_factor', 8, 2)->nullable(); // Por ejemplo: un vial de 50 ml puede equivaler a 500 unidades
            $table->string('image');
            $table->integer('min');
            $table->decimal('tax', 8, 2);
            $table->boolean('status');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
