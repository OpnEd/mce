<?php

namespace App\Http\Controllers;

use App\Models\Api\ExternalOrder;
use App\Models\Api\ExternalOrderItem;
use App\Http\Requests\ReceiveExternalOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Validator;

class ExternalOrderController extends Controller
{
    public function receive(ReceiveExternalOrderRequest $request)
    {
        // 0 Obtener Request ID (OBLIGATORIO)
        $requestId = $request->header('X-Request-Id');

        if (! $requestId) {
            return response()->json([
                'message' => 'Missing X-Request-Id header'
            ], 400);
        }
        // 1) Validar payload según reglas definidas
        $data = $request->validated();

        try {

            return DB::transaction(function () use ($request, $data, $requestId) {

                // 2 Registrar request técnica (IDEMPOTENCIA DURA)
                DB::table('external_requests')->insert([
                    'request_id'        => $requestId,
                    'external_order_id' => $data['external_order_id'],
                    'status'            => 'processing',
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);

                // 3 Crear orden de negocio
                $order = ExternalOrder::create([
                    'external_order_id'    => $data['external_order_id'],
                    'request_id'          => $requestId,
                    'external_created_at'  => $data['created_at'],
                    // Asignar datos de cliente
                    'customer_name'    => $data['customer']['name'],
                    'customer_phone'   => $data['customer']['phone'],
                    'customer_email'   => $data['customer']['email'] ?? null,
                    'customer_address' => $data['customer']['address'],
                    'customer_lat'     => $data['customer']['lat'] ?? null,
                    'customer_lng'     => $data['customer']['lng'] ?? null,
                    // Asignar datos meta si existen
                    'notify_radius_m'  => $data['meta']['notify_radius_m'] ?? null,
                    'notes'            => $data['meta']['notes'] ?? null,
                    'payment_method'  => $data['meta']['payment_method'] ?? null,
                    'estimated_total'  => $data['meta']['estimated_total'] ?? null,
                ]);

                // Crear cada ítem relacionado
                foreach ($data['items'] as $item) {
                    $order->items()->create([
                        'sku'               => $item['sku'] ?? null,
                        'product_id'        => $item['product_id'] ?? null,
                        'name'              => $item['name'],
                        'qty'               => $item['qty'],
                        'price'             => $item['price'],
                    ]);
                }
                // 5 Marcar request como completada
                DB::table('external_requests')
                    ->where('request_id', $requestId)
                    ->update([
                        'status'     => 'completed',
                        'updated_at' => now(),
                    ]);

                return response()->json([
                    'status'            => 'created',
                    'external_order_id' => $order->external_order_id,
                ], 201);
            });
        } catch (QueryException $e) {

            // 6 Violación de unicidad = REINTENTO
            if ($e->getCode() === '23000') {
                return response()->json([
                    'status'            => 'already_received',
                    'external_order_id' => $data['external_order_id'],
                ], 200);
            }

            throw $e;
        }
    }
}
