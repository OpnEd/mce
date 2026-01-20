<?php

namespace App\Observers;

use App\Models\Api\ExternalOrder;
use App\Jobs\NotifyNearbyTeamsJob;
use Illuminate\Support\Facades\Log;

class ExternalOrderObserver
{
    /**
     * Handle the ExternalOrder "created" event.
     */
    public function created(ExternalOrder $externalOrder): void
    {
        // Solo disparar si team_id === null (orden abierta para tomar)
        if ($externalOrder->team_id !== null) {
            Log::info('ExternalOrderObserver: created but already assigned; skipping notify job', [
                'external_order_id' => $externalOrder->external_order_id,
                'order_id' => $externalOrder->id,
                'team_id' => $externalOrder->team_id,
            ]);
            return;
        }

        // Dispatch the job AFTER DB commit to ensure the order is persisted
        // and visible to the worker.
        NotifyNearbyTeamsJob::dispatch($externalOrder->id)->afterCommit();

        Log::info('ExternalOrderObserver: NotifyNearbyTeamsJob dispatched (afterCommit)', [
            'external_order_id' => $externalOrder->external_order_id,
            'order_id' => $externalOrder->id,
        ]);
    }

    // Opcional: puedes implementar updated/deleted si lo requieres.
}
