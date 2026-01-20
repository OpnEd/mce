<?php

namespace App\Jobs;

use App\Models\Api\ExternalOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Team;
use App\Notifications\NewExternalOrderNotification;
use Illuminate\Support\Facades\Notification;

class FindNearbyTeamsJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected ExternalOrder $externalOrder;
    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(ExternalOrder $externalOrder)
    {
        $this->externalOrder = $externalOrder;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $lat = $this->externalOrder->customer_lat;
        $lng = $this->externalOrder->customer_lng;

        if (! $lat || ! $lng) {
            // Si falta geolocalización, podemos no notificar o aplicar fallback
            return;
        }

        // Distancia en metros
        $radiusMeters = 5000;

        // ---- Opción PostGIS (preferida si la tienes) ----
        if (config('database.default') === 'pgsql') {
            $teams = Team::selectRaw("id, name, latitude, longitude, ST_DistanceSphere(ST_MakePoint(longitude, latitude), ST_MakePoint(?,?)) AS distance_m", [$lng, $lat])
                ->whereRaw("ST_DWithin(ST_SetSRID(ST_MakePoint(longitude, latitude),4326)::geography, ST_SetSRID(ST_MakePoint(?,?),4326)::geography, ?)",
                    [$lng, $lat, $radiusMeters])
                ->where('is_active', true)
                ->orderBy('distance_m')
                ->get();
        } else {
            // ---- Opción Haversine general ----
            // devuelve distance_km
            $teams = Team::selectRaw("id, name, latitude, longitude, ( 6371000 * acos(
                cos(radians(?)) *
                cos(radians(latitude)) *
                cos(radians(longitude) - radians(?)) +
                sin(radians(?)) * sin(radians(latitude))
            )) AS distance_m", [$lat, $lng, $lat])
                ->where('is_active', true)
                ->havingRaw('distance_m <= ?', [$radiusMeters])
                ->orderBy('distance_m')
                ->get();
        }

        if ($teams->isEmpty()) {
            // Opcional: marcar external order como 'expired' o 'no_candidates'
            $this->externalOrder->update(['status' => 'no_candidates']);
            return;
        }

        // Notificar a cada team (a sus usuarios con rol 'operator' o 'admin')
        foreach ($teams as $teamData) {
            // Recupera el modelo Team real (si usaste selectRaw quizá sea stdClass)
            $team = Team::find($teamData->id);
            if (! $team) continue;

            // Obtener usuarios del team que deben recibir notificaciones
            $usersToNotify = $team->users()->whereIn('role', ['admin','operator'])->get();

            if ($usersToNotify->isEmpty()) continue;

            // Enviar notificación (database + broadcast)
            Notification::send($usersToNotify, new NewExternalOrderNotification($this->externalOrder, $team, (int) ($teamData->distance_m ?? ($teamData->distance_km*1000 ?? 0))));
        }

        // Opcional: actualizar external_order status
        $this->externalOrder->update(['status' => 'notified']);
    }
}
