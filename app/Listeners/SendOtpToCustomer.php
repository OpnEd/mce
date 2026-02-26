<?php

namespace App\Listeners;

use App\Events\ExternalOrderAccepted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendOtpToCustomer
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
    public function handle(ExternalOrderAccepted $event): void
    {
        $order = $event->order;
        $otp = $event->otpCode;

        Log::info("Enviando OTP al cliente", [
            'order_id' => $order->id,
            'customer_phone' => $order->customer_phone,
            'otp' => $otp,
        ]);

        // Aquí integrar con Twilio/WhatsApp
        // TwilioService::sendWhatsApp(
        //     $order->customer_phone,
        //     "Hola {$order->customer_name}, tu droguería {$order->team->name} está preparando tu pedido. " .
        //     "Código de entrega: {$otp}. No compartas este código."
        // );
    }
}
