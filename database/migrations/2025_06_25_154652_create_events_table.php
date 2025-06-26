<?php

use App\Models\Schedule;
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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Schedule::class)->constrained()->cascadeOnDelete()->nullable();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->enum('type', ['event','task','milestone'])->default('event');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('has_time')->default(false);
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('done')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
