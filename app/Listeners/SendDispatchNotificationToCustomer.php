<?php

namespace App\Listeners;

use App\Events\ExternalOrderDispatched;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendDispatchNotificationToCustomer
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
    public function handle(ExternalOrderDispatched $event): void
    {
        $order = $event->order;

        Log::info("Notificando despacho al cliente", [
            'order_id' => $order->id,
            'otp' => $order->otp_code,
        ]);

        // TwilioService::sendWhatsApp(
        //     $order->customer_phone,
        //     "Tu pedido de {$order->team->name} va en camino. " .
        //     "Cuando llegue, entrega el código {$order->otp_code} al repartidor para confirmar."
        // );
    }
}
