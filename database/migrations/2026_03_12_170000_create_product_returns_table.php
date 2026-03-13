<?php

use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(User::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->foreignIdFor(Supplier::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Purchase::class)
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->dateTime('received_at')->nullable();
            $table->string('type')->nullable();
            $table->string('priority')->default('media');
            $table->string('status')->default('registrado');
            $table->unsignedSmallInteger('response_time_limit_days')->nullable();
            $table->dateTime('response_due_at')->nullable();
            $table->string('return_code')->nullable();
            $table->string('supplier_reference')->nullable();
            $table->json('items')->nullable();
            $table->text('reason')->nullable();
            $table->text('observations')->nullable();
            $table->unsignedInteger('total_items')->nullable();
            $table->unsignedInteger('total_value')->nullable();
            $table->text('supplier_response')->nullable();
            $table->string('authorization_code')->nullable();
            $table->string('credit_note_number')->nullable();
            $table->dateTime('responded_at')->nullable();
            $table->dateTime('closed_at')->nullable();
            $table->boolean('requires_follow_up')->default(false);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['team_id', 'received_at']);
            $table->index(['team_id', 'status']);
            $table->index(['team_id', 'supplier_id']);
            $table->index(['return_code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_returns');
    }
};
