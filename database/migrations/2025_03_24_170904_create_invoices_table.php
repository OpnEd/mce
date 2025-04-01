<?php

use App\Models\Sale;
use App\Models\Supplier;
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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)->constrained();
            $table->foreignIdFor(Sale::class)->nullable()->constrained();
            $table->foreignIdFor(Supplier::class)->nullable()->constrained();
            $table->string('code')->unique();
            $table->decimal('amount', 10, 2);
            $table->boolean('is_our')->default(true);
            $table->date('issued_date');
            $table->json('data')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
