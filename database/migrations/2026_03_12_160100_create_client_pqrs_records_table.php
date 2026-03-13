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
        Schema::create('client_pqrs_records', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(User::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->dateTime('received_at')->nullable();
            $table->string('channel')->nullable();
            $table->string('type')->nullable();
            $table->string('priority')->default('media');
            $table->string('status')->default('recibido');
            $table->unsignedSmallInteger('response_time_limit_days')->nullable();
            $table->dateTime('response_due_at')->nullable();
            $table->string('tracking_code')->nullable();
            $table->string('subject');
            $table->text('description')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_document')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('client_email')->nullable();
            $table->boolean('is_anonymous')->default(false);
            $table->string('responsible_area')->nullable();
            $table->text('response')->nullable();
            $table->dateTime('responded_at')->nullable();
            $table->text('corrective_action')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->boolean('requires_follow_up')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['team_id', 'received_at']);
            $table->index(['team_id', 'type']);
            $table->index(['team_id', 'status']);
            $table->index(['tracking_code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('client_pqrs_records');
    }
};
