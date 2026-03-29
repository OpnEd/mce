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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('resource_type'); // Model class name (e.g., 'Course', 'Module')
            $table->unsignedBigInteger('resource_id'); // ID of the resource being audited
            $table->string('action'); // create, update, delete, read
            $table->text('changes')->nullable(); // JSON with old/new values
            $table->text('description')->nullable(); // Human-readable description
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            // Indexes for fast lookups
            $table->index('team_id');
            $table->index('user_id');
            $table->index(['resource_type', 'resource_id']);
            $table->index('action');
            $table->index('created_at');
            $table->fullText(['description']); // For full-text search
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
