<?php

namespace App\Http\Controllers;

use App\Models\Api\ExternalOrder;
use App\Models\Api\ExternalOrderItem;
use App\Http\Requests\ReceiveExternalOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Validator;

/**
 * ExternalOrderController
 *
 * Controlador encargado de gestionar la recepción y procesamiento de órdenes externas.
 * Implementa patrones de idempotencia mediante header X-Request-Id para garantizar
 * que órdenes duplicadas se manejen correctamente.
 *
 * @package App\Http\Controllers
 * @author Desarrollo
 */
class ExternalOrderController extends Controller
{
    /**
     * Recibe y procesa una orden externa.
     *
     * Este método implementa el flujo completo de recepción de órdenes:
     * 1. Valida el header X-Request-Id (obligatorio para idempotencia)
     * 2. Valida el payload según reglas de ReceiveExternalOrderRequest
     * 3. Registra la solicitud en tabla de control (external_requests)
     * 4. Crea la orden de negocio con sus items relacionados
     * 5. Marca el request como completado
     * 6. Detecta reintentos mediante violación de constraint UNIQUE
     *
     * @param ReceiveExternalOrderRequest $request Solicitud validada con estructura:
     *                                             - external_order_id: ID único de la orden
     *                                             - created_at: Timestamp de creación
     *                                             - customer: Datos del cliente
     *                                             - items: Ítems de la orden
     *                                             - meta: Datos adicionales opcionales
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con estado:
     *                                        - 201: Orden creada exitosamente
     *                                        - 200: Orden ya existe (reintento detectado)
     *                                        - 400: Falta header X-Request-Id
     *
     * @throws QueryException Si ocurre un error de base de datos diferente a violación de UNIQUE
     */
    public function receive(ReceiveExternalOrderRequest $request)
    {
        // Paso 0: Obtener y validar header X-Request-Id (OBLIGATORIO)
        // Este identificador es crítico para garantizar idempotencia.
        // Si se reintenta la misma solicitud con el mismo ID, se detectará como duplicada.
        $requestId = $request->header('X-Request-Id');

        if (! $requestId) {
            return response()->json([
                'message' => 'Missing X-Request-Id header'
            ], 400);
        }

        // Paso 1: Validar payload según reglas definidas en ReceiveExternalOrderRequest
        $data = $request->validated();

        try {
            // Paso 2-5: Ejecutar operación de base de datos en transacción
            // Garantiza consistencia: si algo falla, todo se revierte
            return DB::transaction(function () use ($request, $data, $requestId) {

                // Paso 2: Registrar solicitud técnica en tabla de control
                // Esto implementa idempotencia DURA: antes de procesar, registramos
                // que estamos procesando esta solicitud. Si se reintenta, fallará el UNIQUE
                DB::table('external_requests')->insert([
                    'request_id'        => $requestId,
                    'external_order_id' => $data['external_order_id'],
                    'status'            => 'processing',
                    'created_at'        => now(),
                    'updated_at'        => now(),
                ]);

                // Paso 3: Crear orden de negocio principal
                // Mapear datos del payload a la estructura de modelo ExternalOrder
                $order = ExternalOrder::create([
                    'external_order_id'    => $data['external_order_id'],
                    'request_id'          => $requestId,
                    'external_created_at'  => $data['created_at'],
                    
                    // Datos del cliente
                    'customer_id'      => $data['customer']['id'],
                    'customer_name'    => $data['customer']['name'],
                    'customer_phone'   => $data['customer']['phone'],
                    'customer_email'   => $data['customer']['email'] ?? null,
                    'customer_address' => $data['customer']['address'],
                    'customer_lat'     => $data['customer']['lat'] ?? null,
                    'customer_lng'     => $data['customer']['lng'] ?? null,
                    
                    // Datos adicionales (meta) opcionales
                    'notify_radius_m'  => $data['meta']['notify_radius_m'] ?? null,
                    'notes'            => $data['meta']['notes'] ?? null,
                    'payment_method'  => $data['meta']['payment_method'] ?? null,
                    'estimated_total'  => $data['meta']['estimated_total'] ?? null,
                ]);
                // Observer: ExternalOrderObserver se encargará de notificar a los equipos cercanos

                // Paso 4: Crear ítems de la orden
                // Procesar cada artículo del array de items y vincularlo a la orden principal
                foreach ($data['items'] as $item) {
                    $order->items()->create([
                        'sku'               => $item['sku'] ?? null,
                        'product_id'        => $item['product_id'] ?? null,
                        'name'              => $item['name'],
                        'qty'               => $item['qty'],
                        'price'             => $item['price'],
                    ]);
                }

                // Paso 5: Marcar solicitud como completada
                // Actualizar el estado en la tabla de control a 'completed'
                DB::table('external_requests')
                    ->where('request_id', $requestId)
                    ->update([
                        'status'     => 'completed',
                        'updated_at' => now(),
                    ]);

                // Retornar respuesta exitosa (201 Created)
                return response()->json([
                    'status'            => 'created',
                    'external_order_id' => $order->external_order_id,
                ], 201);
            });
        } catch (QueryException $e) {

            // Paso 6: Manejar reintentos (detección de duplicados)
            // Código de error 23000 = UNIQUE constraint violation
            // Si llegamos aquí, significa que el mismo X-Request-Id ya fue procesado
            // Retornamos 200 OK para indicar que la solicitud ya fue recibida
            if ($e->getCode() === '23000') {
                return response()->json([
                    'status'            => 'already_received',
                    'external_order_id' => $data['external_order_id'],
                ], 200);
            }

            // Re-lanzar excepción si es otro tipo de error de base de datos
            throw $e;
        }
    }
}
