<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('enrollments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();

            // Certificate details
            $table->string('certificate_number')->unique()->index(); // Format: CERT-YYYYMMDD-XXXXX
            $table->string('title'); // Nombre del certificado/curso
            $table->text('description')->nullable(); // Descripción del certificado
            $table->string('issuer')->default('D-Origin 2.0'); // Entidad emisora

            // Dates
            $table->date('issued_at')->index(); // Fecha de emisión
            $table->date('valid_until')->nullable(); // Fecha de vencimiento (si aplica)

            // Scoring & Status
            $table->decimal('final_score', 5, 2)->nullable(); // Puntuación final
            $table->string('status')->default('pending'); // pending, issued, revoked
            $table->boolean('is_verified')->default(false); // ¿Verificado?
            $table->text('verification_token')->nullable(); // Token para verificación online

            // File Storage
            $table->string('pdf_path')->nullable(); // Ruta al PDF en storage
            $table->string('pdf_filename')->nullable(); // Nombre del archivo PDF
            $table->integer('pdf_size')->nullable(); // Tamaño del PDF en bytes

            // Metadata
            $table->string('template_used')->default('default'); // Template usado
            $table->json('metadata')->nullable(); // Datos adicionales (firma digital, etc)
            $table->text('notes')->nullable(); // Notas internas

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['user_id', 'issued_at']);
            $table->index(['course_id', 'status']);
            $table->index(['team_id', 'issued_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
