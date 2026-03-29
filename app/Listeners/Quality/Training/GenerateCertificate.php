<?php

namespace App\Listeners\Quality\Training;

use App\Events\Quality\Training\EnrollmentCompleted;
use App\Models\Quality\Training\Certificate;
use App\Notifications\Quality\Training\CertificateIssuedNotification;
use App\Services\Quality\CertificateService;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Throwable;

class GenerateCertificate implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected CertificateService $certificateService
    ) {}

    /**
     * Handle the event.
     */
    public function handle(EnrollmentCompleted $event): void
    {
        try {
            // Crear certificado
            $certificate = $this->certificateService->generateCertificate(
                enrollment: $event->enrollment,
                user: $event->enrollment->user,
                course: $event->enrollment->course,
                finalScore: $event->finalScore,
                templateName: 'default'
            );

            // Actualizar campos legacy en Enrollment
            $event->enrollment->update([
                'certificated_at' => $certificate->issued_at,
                'certificate_url' => $certificate->getPdfDownloadUrl(),
                'score_final' => $event->finalScore,
            ]);

            // Notificar al usuario
            $this->notifyUser($event->enrollment, $certificate);

            \Log::info('Certificate generated successfully', [
                'certificate_id' => $certificate->id,
                'enrollment_id' => $event->enrollment->id,
                'user_id' => $event->enrollment->user_id,
            ]);
        } catch (Throwable $e) {
            \Log::error('Failed to generate certificate', [
                'enrollment_id' => $event->enrollment->id,
                'user_id' => $event->enrollment->user_id,
                'error' => $e->getMessage(),
            ]);

            // Re-throw para que el job se reintente
            throw $e;
        }
    }

    /**
     * Notificar al usuario sobre el certificado emitido
     */
    private function notifyUser($enrollment, Certificate $certificate): void
    {
        try {
            // Enviar notificación por email
            $enrollment->user->notify(
                new CertificateIssuedNotification($certificate, $enrollment->course)
            );

            // También podemos enviar notificación en Filament (si el usuario está logged in)
            // Esto se mostraría en el dashboard
            if (auth()->check() && auth()->id() === $enrollment->user_id) {
                Notification::make()
                    ->title('¡Certificado Emitido!')
                    ->body("Tu certificado para {$enrollment->course->title} ha sido generado.")
                    ->success()
                    ->send();
            }
        } catch (Throwable $e) {
            \Log::warning('Failed to notify user about certificate', [
                'user_id' => $enrollment->user_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Determine if the listener should be queued (ya implementado en la interfaz)
     */
    public function shouldQueue(): bool
    {
        return true;
    }

    /**
     * Número de intentos para reintento
     */
    public int $tries = 3;

    /**
     * Tiempo de espera entre reintentos (segundos)
     */
    public int $backoff = 60;
}
