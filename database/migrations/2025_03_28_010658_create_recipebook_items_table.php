<?php

use App\Models\Inventory;
use App\Models\Recipebook;
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
        Schema::create('recipebook_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Recipebook::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Inventory::class)->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recipebook_items');
    }
};
