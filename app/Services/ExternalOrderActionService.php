<?php

namespace App\Services;

use App\Events\ExternalOrderDelivered;
use App\Events\ExternalOrderDispatched;
use App\Jobs\CreateSaleFromExternalOrder;
use App\Jobs\RejectedExternalOrderJob;
use App\Models\Api\ExternalOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * ExternalOrderActionService
 *
 * Servicio de dominio que encapsula todas las acciones y transiciones de estado
 * para órdenes externas. Centraliza la lógica de negocio y garantiza consistencia
 * en todas las operaciones.
 *
 * Responsabilidades:
 * - Tomar una orden (asignación a equipo)
 * - Registrar venta y generar OTP
 * - Despachar orden (marcar en tránsito)
 * - Validar OTP y confirmar entrega
 * - Rechazar orden y devolver a candidatos
 *
 * @package App\Services
 * @author Desarrollo
 */
class ExternalOrderActionService
{
    /**
     * Toma una orden externa asignándola al equipo actual
     *
     * Implementa control de concurrencia mediante pessimistic locking para evitar
     * race conditions cuando múltiples equipos intentan tomar la misma orden.
     *
     * Logs:
     * - DEBUG: inicio con datos de orden y equipo
     * - DEBUG: bloqueo pessimista adquirido
     * - INFO: orden asignada exitosamente
     * - ERROR: orden ya asignada o error durante transacción
     *
     * @param ExternalOrder $order Orden a tomar
     * @param int $teamId ID del equipo que toma la orden
     * @return bool True si la orden fue tomada exitosamente, false si ya estaba asignada
     * @throws Throwable
     */
    public function takeOrder(ExternalOrder $order, int $teamId): bool
    {
        $orderId = $order->id;
        $externalOrderId = $order->external_order_id;

        try {
            Log::debug('ExternalOrderActionService::takeOrder - iniciando', [
                'order_id' => $orderId,
                'external_order_id' => $externalOrderId,
                'team_id' => $teamId,
                'current_status' => $order->status,
                'current_team_id' => $order->team_id,
            ]);

            return DB::transaction(function () use ($order, $teamId, $orderId, $externalOrderId) {
                // Pessimistic locking: bloquear y recargar para verificar estado actual
                Log::debug('ExternalOrderActionService::takeOrder - aplicando pessimistic lock', [
                    'order_id' => $orderId,
                ]);

                $lockedOrder = $order->newQuery()
                    ->where('id', $order->id)
                    ->whereNull('team_id')
                    ->lockForUpdate()
                    ->first();

                if (!$lockedOrder) {
                    Log::warning('ExternalOrderActionService::takeOrder - orden ya asignada o no disponible', [
                        'order_id' => $orderId,
                        'external_order_id' => $externalOrderId,
                        'team_id' => $teamId,
                    ]);
                    return false;
                }

                // Asignar equipo y cambiar estado a CLAIMED
                Log::debug('ExternalOrderActionService::takeOrder - actualizando orden', [
                    'order_id' => $orderId,
                    'new_team_id' => $teamId,
                    'new_status' => 'CLAIMED',
                ]);

                $lockedOrder->update([
                    'team_id' => $teamId,
                    'status' => 'CLAIMED',
                    'claimed_at' => now(),
                    'claimed_by' => Auth::id(),
                ]);

                Log::info('ExternalOrderActionService::takeOrder - orden asignada exitosamente', [
                    'order_id' => $orderId,
                    'external_order_id' => $externalOrderId,
                    'team_id' => $teamId,
                    'claimed_by_user_id' => Auth::id(),
                ]);

                return true;
            });

        } catch (Throwable $e) {
            Log::error('ExternalOrderActionService::takeOrder - error durante transacción', [
                'order_id' => $orderId,
                'external_order_id' => $externalOrderId,
                'team_id' => $teamId,
                'exception' => $e::class,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }

    /**
     * Registra venta y genera código OTP para la orden
     *
     * Encola un job asincrónico que:
     * - Crea la venta en el sistema
     * - Genera un código OTP de 4 dígitos
     * - Notifica al cliente
     *
     * Logs:
     * - DEBUG: inicio con datos de orden y equipo
     * - INFO: job encolado exitosamente
     * - ERROR: cualquier excepción durante dispatch
     *
     * @param ExternalOrder $order Orden a registrar
     * @return void
     * @throws Throwable Si falla el dispatch del job
     */
    public function registerSale(ExternalOrder $order): void
    {
        try {
            $orderId = $order->id;
            $externalOrderId = $order->external_order_id;
            $teamId = $order->team_id;
            $currentStatus = $order->status;

            // Log inicio del registro
            Log::debug('ExternalOrderActionService::registerSale - iniciando', [
                'order_id' => $orderId,
                'external_order_id' => $externalOrderId,
                'team_id' => $teamId,
                'current_status' => $currentStatus,
                'has_otp' => (bool) $order->otp_code,
            ]);

            // Encolar job asincrónico para crear venta y OTP
            $dispatchedJob = CreateSaleFromExternalOrder::dispatch($order);

            // Log éxito del dispatch
            Log::info('ExternalOrderActionService::registerSale - job encolado exitosamente', [
                'order_id' => $orderId,
                'external_order_id' => $externalOrderId,
                'job_class' => CreateSaleFromExternalOrder::class,
                'queue' => config('queue.default'),
            ]);

            // Debug: solo en local o APP_DEBUG - detalles extra
            if (app()->isLocal() || config('app.debug')) {
                Log::debug('ExternalOrderActionService::registerSale - dispatch details', [
                    'dispatched_object' => gettype($dispatchedJob),
                    'user_id' => Auth::id(),
                    'timestamp' => now()->toIso8601String(),
                ]);
            }

        } catch (Throwable $e) {
            // Log detallado del error
            Log::error('ExternalOrderActionService::registerSale - error durante dispatch', [
                'order_id' => $order->id,
                'external_order_id' => $order->external_order_id,
                'team_id' => $order->team_id,
                'exception' => $e::class,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Re-lanzar excepción para que el handler la capture
            throw $e;
        }
    }

    /**
     * Marca la orden como en tránsito
     *
     * Cambios:
     * - Status: PREPARATION → IN_TRANSIT
     * - Valida que exista OTP antes de despachar
     * - Dispara evento de broadcast al cliente
     *
     * Logs:
     * - DEBUG: inicio con datos de orden
     * - DEBUG: validación de OTP realizada
     * - INFO: orden marcada en tránsito
     * - DEBUG: evento ExternalOrderDispatched disparado
     * - ERROR: si no existe OTP o error durante proceso
     *
     * @param ExternalOrder $order Orden a despachar
     * @return void
     * @throws \Exception Si no existe OTP
     */
    public function dispatchOrder(ExternalOrder $order): void
    {
        $orderId = $order->id;
        $externalOrderId = $order->external_order_id;
        $currentStatus = $order->status;

        try {
            Log::debug('ExternalOrderActionService::dispatchOrder - iniciando', [
                'order_id' => $orderId,
                'external_order_id' => $externalOrderId,
                'current_status' => $currentStatus,
                'has_otp' => (bool) $order->otp_code,
            ]);

            if (!$order->otp_code) {
                Log::error('ExternalOrderActionService::dispatchOrder - OTP no generado', [
                    'order_id' => $orderId,
                    'external_order_id' => $externalOrderId,
                ]);
                throw new \Exception('No hay código OTP generado. Registra la venta primero.');
            }

            Log::debug('ExternalOrderActionService::dispatchOrder - OTP validado, actualizando estado', [
                'order_id' => $orderId,
                'otp_code' => substr($order->otp_code, 0, 2) . '**', // Log parcial por seguridad
            ]);

            $order->update(['status' => 'IN_TRANSIT']);

            Log::info('ExternalOrderActionService::dispatchOrder - orden marcada en tránsito', [
                'order_id' => $orderId,
                'external_order_id' => $externalOrderId,
                'new_status' => 'IN_TRANSIT',
            ]);

            // Disparar evento para notificar al cliente
            Log::debug('ExternalOrderActionService::dispatchOrder - disparando evento', [
                'order_id' => $orderId,
                'event' => ExternalOrderDispatched::class,
            ]);

            ExternalOrderDispatched::dispatch($order);

        } catch (\Exception $e) {
            Log::error('ExternalOrderActionService::dispatchOrder - error durante despacho', [
                'order_id' => $orderId,
                'external_order_id' => $externalOrderId,
                'exception' => $e::class,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }

    /**
     * Valida el código OTP y confirma la entrega
     *
     * Cambios:
     * - Verifica que el OTP sea correcto
     * - Status: IN_TRANSIT → DELIVERED_VERIFIED
     * - Dispara evento de entrega completada
     *
     * Logs:
     * - DEBUG: inicio con datos de orden
     * - DEBUG: validación OTP realizada
     * - INFO: si OTP es válido y orden confirmada
     * - WARNING: si OTP es inválido
     * - DEBUG: evento ExternalOrderDelivered disparado
     * - ERROR: cualquier excepción durante proceso
     *
     * @param ExternalOrder $order Orden a validar
     * @param string $otpInput Código OTP ingresado por el cliente
     * @return bool True si OTP es válido, false si es incorrecto
     */
    public function validateOtp(ExternalOrder $order, string $otpInput): bool
    {
        $orderId = $order->id;
        $externalOrderId = $order->external_order_id;

        try {
            Log::debug('ExternalOrderActionService::validateOtp - iniciando validación', [
                'order_id' => $orderId,
                'external_order_id' => $externalOrderId,
                'current_status' => $order->status,
                'otp_input_length' => strlen($otpInput),
            ]);

            $isValid = $order->verifyOtp($otpInput);

            if (!$isValid) {
                Log::warning('ExternalOrderActionService::validateOtp - OTP inválido', [
                    'order_id' => $orderId,
                    'external_order_id' => $externalOrderId,
                    'otp_input_length' => strlen($otpInput),
                ]);
                return false;
            }

            Log::debug('ExternalOrderActionService::validateOtp - OTP válido, actualizando estado', [
                'order_id' => $orderId,
            ]);

            // Actualizar status a DELIVERED_VERIFIED
            $order->update(['status' => 'DELIVERED_VERIFIED']);

            Log::info('ExternalOrderActionService::validateOtp - orden confirmada como entregada', [
                'order_id' => $orderId,
                'external_order_id' => $externalOrderId,
                'new_status' => 'DELIVERED_VERIFIED',
            ]);

            // Disparar evento de entrega completada
            Log::debug('ExternalOrderActionService::validateOtp - disparando evento', [
                'order_id' => $orderId,
                'event' => ExternalOrderDelivered::class,
            ]);

            ExternalOrderDelivered::dispatch($order);

            return true;

        } catch (Throwable $e) {
            Log::error('ExternalOrderActionService::validateOtp - error durante validación', [
                'order_id' => $orderId,
                'external_order_id' => $externalOrderId,
                'exception' => $e::class,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }

    /**
     * Rechaza una orden previamente tomada
     *
     * Retorna la orden al estado NOTIFIED permitiendo que otros equipos candidatos
     * puedan tomarla. Encola un job para notificar a los candidatos.
     *
     * Cambios:
     * - Limpia: team_id, claimed_at, claimed_by
     * - Status: CLAIMED → NOTIFIED
     * - Encola: RejectedExternalOrderJob para notificar candidatos
     *
     * Logs:
     * - DEBUG: inicio con datos de orden y equipo
     * - DEBUG: aplicando pessimistic lock
     * - ERROR: si el equipo no tiene autorización
     * - DEBUG: desasignando orden
     * - INFO: orden rechazada exitosamente
     * - DEBUG: job encolado para notificar candidatos
     * - ERROR: cualquier excepción durante rechazo
     *
     * @param ExternalOrder $order Orden a rechazar
     * @param int $teamId ID del equipo que rechaza (para validación)
     * @return void
     * @throws Throwable
     * @throws \Exception Si el equipo no tiene autorización
     */
    public function rejectOrder(ExternalOrder $order, int $teamId): void
    {
        $orderId = $order->id;
        $externalOrderId = $order->external_order_id;

        try {
            Log::debug('ExternalOrderActionService::rejectOrder - iniciando', [
                'order_id' => $orderId,
                'external_order_id' => $externalOrderId,
                'team_id' => $teamId,
                'current_status' => $order->status,
                'current_team_id' => $order->team_id,
            ]);

            DB::transaction(function () use ($order, $teamId, $orderId, $externalOrderId) {
                // Pessimistic locking para validar autorización
                Log::debug('ExternalOrderActionService::rejectOrder - aplicando pessimistic lock', [
                    'order_id' => $orderId,
                    'team_id' => $teamId,
                ]);

                $lockedOrder = $order->newQuery()
                    ->where('id', $order->id)
                    ->where('team_id', $teamId)
                    ->lockForUpdate()
                    ->first();

                if (!$lockedOrder) {
                    Log::error('ExternalOrderActionService::rejectOrder - autorización denegada', [
                        'order_id' => $orderId,
                        'external_order_id' => $externalOrderId,
                        'team_id' => $teamId,
                        'current_team_id' => $order->team_id,
                    ]);
                    throw new \Exception('No tienes autorización para rechazar esta orden.');
                }

                // Desasignar y cambiar status de vuelta a NOTIFIED
                Log::debug('ExternalOrderActionService::rejectOrder - desasignando orden', [
                    'order_id' => $orderId,
                    'team_id' => $teamId,
                ]);

                $lockedOrder->update([
                    'team_id' => null,
                    'claimed_at' => null,
                    'claimed_by' => null,
                    'status' => 'NOTIFIED',
                ]);

                Log::info('ExternalOrderActionService::rejectOrder - orden rechazada y devuelta a candidatos', [
                    'order_id' => $orderId,
                    'external_order_id' => $externalOrderId,
                    'rejected_by_team_id' => $teamId,
                    'new_status' => 'NOTIFIED',
                ]);

                // Encolar job para notificar a candidatos
                Log::debug('ExternalOrderActionService::rejectOrder - encolando job de notificación', [
                    'order_id' => $orderId,
                    'job' => RejectedExternalOrderJob::class,
                ]);

                RejectedExternalOrderJob::dispatch($order->id);
            });

        } catch (Throwable $e) {
            Log::error('ExternalOrderActionService::rejectOrder - error durante rechazo', [
                'order_id' => $orderId,
                'external_order_id' => $externalOrderId,
                'team_id' => $teamId,
                'exception' => $e::class,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }
}
