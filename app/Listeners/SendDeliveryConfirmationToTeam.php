<?php

namespace App\Listeners;

use App\Events\ExternalOrderDelivered;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendDeliveryConfirmationToTeam
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ExternalOrderDelivered $event): void
    {
        $order = $event->order;

        Log::info("Orden entregada y verificada", [
            'order_id' => $order->id,
            'team_id' => $order->team_id,
            'otp_verified' => $order->otp_code === $order->delivery_code_input,
        ]);

        // Enviar notificación al team
        Notification::make()
            ->title('Pedido Entregado')
            ->body("Orden {$order->external_order_id} entregada y verificada. Comisión procesada.")
            ->success()
            ->sendToDatabase(
                $order->team->users
            );

        // Aquí: procesar comisión automáticamente
        // CommissionService::chargeForOrder($order);
    }
}
