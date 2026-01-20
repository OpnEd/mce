<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;


class VerifyExternalSignature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $signatureHeader = $request->header('X-Signature');
        $requestId       = $request->header('X-Request-Id');

        if (!$signatureHeader || !$requestId) {
            return response()->json([
                'message' => 'Missing signature headers',
            ], 401);
        }

        // El body crudo (IMPORTANTE)
        $payload = $request->getContent();

        // Clave secreta (por tenant o global, por ahora global)
        $secret = config('services.external_orders.secret');


        // Construcción del mensaje firmado
        $signedPayload = $requestId . '.' . $payload;

        $computed = hash_hmac('sha256', $signedPayload, $secret);

        // Header esperado: sha256=xxxx
        $expected = 'sha256=' . $computed;

        if (!hash_equals($expected, $signatureHeader)) {
            return response()->json([
                'message' => 'Invalid signature',
            ], 401);
        }

        return $next($request);
    }
}
