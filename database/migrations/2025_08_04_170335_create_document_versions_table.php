<?php

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
        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Team::class)
                  ->constrained()
                  ->cascadeOnDelete();
            $table->foreignId('document_id')
                  ->constrained('documents')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')      // quién hizo el cambio
                  ->constrained('users');
            $table->json('changes');          // qué campos cambiaron { campo: [viejo, nuevo], … }
            $table->text('comment')->nullable();  // razón opcional del cambio
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_versions');
    }
};
