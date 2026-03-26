<?php

use App\Models\Process;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Process::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'owner_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('code')->nullable();
            $table->string('title');
            $table->string('activity')->nullable();
            $table->text('description')->nullable();
            $table->text('cause')->nullable();
            $table->text('consequence')->nullable();
            $table->string('risk_type')->nullable();
            $table->string('impact_area')->nullable();
            $table->text('existing_controls')->nullable();

            $table->unsignedTinyInteger('probability')->nullable();
            $table->unsignedTinyInteger('impact')->nullable();
            $table->unsignedSmallInteger('risk_score')->nullable();
            $table->string('risk_level')->nullable();

            $table->unsignedTinyInteger('residual_probability')->nullable();
            $table->unsignedTinyInteger('residual_impact')->nullable();
            $table->unsignedSmallInteger('residual_score')->nullable();
            $table->string('residual_level')->nullable();

            $table->text('treatment_plan')->nullable();
            $table->string('status')->default('abierto');
            $table->date('review_at')->nullable();
            $table->json('data')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->unique(['team_id', 'code']);
            $table->index(['team_id', 'process_id']);
            $table->index(['risk_level', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risks');
    }
};
