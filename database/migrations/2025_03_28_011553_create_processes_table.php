<?php

use App\Models\ProcessType;
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
        Schema::create('processes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(ProcessType::class)->constrained()->cascadeOnDelete();
            $table->json('records')->nullable();
            $table->string('code')->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            //$table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->json('suppliers')->nullable();
            $table->json('inputs')->nullable();
            $table->json('procedures')->nullable();
            $table->json('outputs')->nullable();
            $table->json('clients')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processes');
    }
};
