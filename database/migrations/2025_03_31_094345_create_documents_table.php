<?php

use App\Models\DocumentCategory;
use App\Models\Process;
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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->foreignIdFor(Process::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(DocumentCategory::class)->constrained()->cascadeOnDelete();
            $table->text('body');
            $table->string('slug')->unique();
            $table->json('records')->nullable();
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
        Schema::dropIfExists('documents');
    }
};
