<?php

use App\Models\Batch;
use App\Models\Dispatch;
use App\Models\PurchaseItem;
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
        Schema::create('dispatch_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Dispatch::class);
            $table->foreignIdFor(PurchaseItem::class);
            $table->foreignIdFor(Batch::class)->nullable();
            $table->smallInteger('quantity');
            $table->decimal('price');
            $table->decimal('total');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dispatch_items');
    }
};
