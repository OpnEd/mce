<?php

namespace App\Jobs;

use App\Models\Api\ExternalOrder;
use App\Models\Api\ExternalOrderTeamCandidate;
use App\Notifications\ExternalOrderRejectedNotification;
use App\Models\Team;
use Filament\Notifications\Events\DatabaseNotificationsSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * RejectedExternalOrderJob
 *
 * Job asincrónico que notifica a los equipos candidatos cuando una orden es rechazada
 * por el equipo que la había tomado, permitiendo que otros equipos la tomen nuevamente.
 *
 * Flujo:
 * 1. Carga la orden rechazada
 * 2. Obtiene los candidatos ordenados por distancia
 * 3. Envía notificación a usuarios de equipos candidatos
 * 4. Dispara evento de broadcast para actualizar UI en tiempo real
 *
 * @package App\Jobs
 * @author Desarrollo
 * @implements ShouldQueue
 */
class RejectedExternalOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * ID de la orden rechazada
     *
     * @var int
     */
    public int $externalOrderId;

    /**
     * Número máximo de reintentos del job
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Constructor del Job
     *
     * @param int $externalOrderId ID de la orden externa rechazada
     */
    public function __construct(int $externalOrderId)
    {
        $this->externalOrderId = $externalOrderId;
    }

    /**
     * Procesa el job: notifica a candidatos que la orden está disponible nuevamente
     *
     * @return void
     * @throws Throwable
     */
    public function handle(): void
    {
        // Paso 1: Cargar la orden rechazada con sus candidatos
        $order = ExternalOrder::with('items', 'candidates.team.users')
            ->find($this->externalOrderId);

        if (!$order) {
            Log::warning('RejectedExternalOrderJob: ExternalOrder not found', ['id' => $this->externalOrderId]);
            return;
        }

        // Validar que la orden está en estado NOTIFIED después de ser rechazada
        if ($order->status !== 'NOTIFIED') {
            Log::warning('RejectedExternalOrderJob: order not in NOTIFIED state, skipping', [
                'order_id' => $order->id,
                'status' => $order->status
            ]);
            return;
        }

        // Paso 2: Obtener candidatos ordenados por distancia (los más cercanos primero)
        $candidates = ExternalOrderTeamCandidate::where('external_order_id', $order->id)
            ->with('team.users')
            ->orderBy('distance_m', 'asc')
            ->get();

        if ($candidates->isEmpty()) {
            Log::info('RejectedExternalOrderJob: no candidates found for order', ['order_id' => $order->id]);
            return;
        }

        // Paso 3: Enviar notificaciones a candidatos
        Log::info('RejectedExternalOrderJob: notifying candidates', [
            'order_id' => $order->id,
            'candidates_count' => $candidates->count(),
        ]);

        $dispatchedCount = 0;

        foreach ($candidates as $candidate) {
            $team = $candidate->team;

            if (!$team || !$team->is_active) {
                continue;
            }

            // Cargar usuarios del equipo si no están cargados
            if (!$team->relationLoaded('users')) {
                $team->loadMissing('users');
            }

            // Notificar a cada usuario activo del equipo
            foreach ($team->users as $user) {
                // Saltar usuarios suspendidos
                if ($user->is_suspended) {
                    continue;
                }

                // Evitar duplicar notificaciones si el usuario ya fue notificado
                $alreadyNotified = $user->notifications()
                    ->where('data->order_id', $order->id)
                    ->where('type', 'App\\Notifications\\ExternalOrderRejectedNotification')
                    ->exists();

                if ($alreadyNotified) {
                    continue;
                }

                try {
                    // Enviar notificación al usuario
                    $user->notify(new ExternalOrderRejectedNotification(
                        $order,
                        $team,
                        (int) round($candidate->distance_m)
                    ));

                    // Disparar evento para refrescar notificaciones en Filament (Livewire/Echo)
                    try {
                        DatabaseNotificationsSent::dispatch($user);
                    } catch (Throwable $e) {
                        Log::warning('RejectedExternalOrderJob: no se pudo enviar evento de broadcast', [
                            'error' => $e->getMessage()
                        ]);
                    }

                    $dispatchedCount++;

                } catch (Throwable $e) {
                    Log::error('RejectedExternalOrderJob: notification send failed', [
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        // Log final: resumen de notificaciones enviadas
        Log::info('RejectedExternalOrderJob: completed successfully', [
            'order_id' => $order->id,
            'external_order_id' => $order->external_order_id,
            'total_notifications' => $dispatchedCount,
            'candidates_count' => $candidates->count(),
        ]);
    }
}
