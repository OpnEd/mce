<?php

use App\Models\ChecklistItem;
use App\Models\Team;
use App\Models\User;
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
        Schema::create('checklist_item_answers', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignIdFor(Team::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(ChecklistItem::class)->constrained()->onDelete('cascade');
            $table->boolean('meets')->default(true);
            $table->boolean('apply')->default(true);
            $table->json('evidence')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_item_answers');
    }
};
