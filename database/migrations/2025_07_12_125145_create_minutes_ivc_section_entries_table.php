<?php

use App\Models\MinutesIvcSection;
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
        Schema::create('minutes_ivc_section_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MinutesIvcSection::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->boolean('apply');
            $table->string('entry_id', 15)->unique();
            $table->enum('criticality', ['critical', 'major', 'minor']); //Critical; Major; Minor
            $table->text('question');
            $table->text('answer')->nullable();
            $table->enum('entry_type', ['informativo', 'evidencia']); //informativo; evidencia
            $table->json('links')->nullable();
            $table->boolean('compliance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('minutes_ivc_section_entries');
    }
};
