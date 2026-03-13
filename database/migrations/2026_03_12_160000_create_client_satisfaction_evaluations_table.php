<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('client_satisfaction_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(User::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->dateTime('evaluated_at')->nullable();
            $table->string('channel')->nullable();
            $table->string('service_area')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('client_name')->nullable();
            $table->string('client_document')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('client_email')->nullable();
            $table->unsignedTinyInteger('overall_score')->nullable();
            $table->unsignedTinyInteger('attention_score')->nullable();
            $table->unsignedTinyInteger('waiting_time_score')->nullable();
            $table->unsignedTinyInteger('availability_score')->nullable();
            $table->unsignedTinyInteger('information_clarity_score')->nullable();
            $table->unsignedTinyInteger('cleanliness_score')->nullable();
            $table->unsignedTinyInteger('facility_score')->nullable();
            $table->boolean('would_recommend')->nullable();
            $table->unsignedTinyInteger('recommendation_score')->nullable();
            $table->boolean('would_return')->nullable();
            $table->text('comments')->nullable();
            $table->boolean('follow_up_required')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['team_id', 'evaluated_at']);
            $table->index(['team_id', 'overall_score']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_satisfaction_evaluations');
    }
};
