<?php

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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->text('objective')->nullable();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->string('color', 7)->default('#000000'); // Default color
            $table->string('icon', 50)->nullable(); // Optional icon field
            $table->boolean('is_cancelled')->default(false); // Indicates if this schedule is canceled
            $table->boolean('is_rescheduled')->default(false); // Indicates if this schedule is rescheduled
            $table->boolean('is_completed')->default(false); // Indicates if this schedule is completed
            $table->boolean('is_in_progress')->default(false); // Indicates if this schedule is in progress
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
