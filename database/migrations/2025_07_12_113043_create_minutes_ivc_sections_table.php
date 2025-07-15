<?php

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
        Schema::create('minutes_ivc_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Team::class)->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('slug');
            $table->smallInteger('order');
            $table->float('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minutes_ivc_sections');
    }
};
