<?php

namespace App\Http\Controllers\Whatsapp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\WhatsAppService;

class WhatsAppWebhookController extends Controller
{    // Verificación inicial del webhook (GET)

    public function __invoke(Request $request)
    {
        if ($request->isMethod('get')) {
            return $this->verifyWebhook($request);
        }

        if ($request->isMethod('post')) {
            return $this->handleIncomingMessage($request);
        }

        return response()->json([], 403);
    }

    public function verifyWebhook(Request $request)
    {
        Log::info('Verifying WhatsApp webhook', ['request' => $request->all()]);
        
        $verifyToken = config('services.whatsapp.verify_token');
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode === 'subscribe' && $token === $verifyToken) {
            return response($challenge, 200)
                ->header('Content-Type', 'text/plain')
                ->header('ngrok-skip-browser-warning', 'true');
        }

        return response('Token inválido', 403);
    }

    public function handleIncomingMessage(Request $request)
    {
    Log::info('🔥 WEBHOOK HIT', [
        'method' => $request->method(),
        'headers' => $request->headers->all(),
        'raw' => $request->getContent(),
    ]);
        $payload = $request->all();

        // Seguridad básica: evitar errores
        if (!isset($payload['entry'][0]['changes'][0]['value'])) {
            return response()->json(['status' => 'ignored'], 200);
        }

        $value = $payload['entry'][0]['changes'][0]['value'];

        if (!isset($value['messages'][0])) {
            return response()->json(['status' => 'no_message'], 200);
        }

        $message = $value['messages'][0];

        $from = $message['from']; // número del usuario
        $text = $message['text']['body'] ?? null;

        /* if ($text) {
            $this->sendAutoReply($from, $text);
        } */

        return response()->json(['status' => 'ok'], 200);
    }

    protected function sendAutoReply(string $to, string $incomingText)
    {
        /* $service = app(WhatsAppService::class);

        $reply = match (true) {
            str_contains(strtolower($incomingText), 'hola') =>
            '👋 Hola, gracias por escribirnos. ¿En qué puedo ayudarte?',

            str_contains(strtolower($incomingText), 'precio') =>
            '💰 Para información de precios, dime el producto que buscas.',

            default =>
            '🤖 Soy un bot automático. Escribe "hola" para comenzar.',
        };

        $service->sendTextMessage($to, $reply); */
    }
}
