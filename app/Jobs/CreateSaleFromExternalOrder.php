<?php

namespace App\Jobs;

use App\Events\ExternalOrderAccepted;
use App\Models\Api\ExternalOrder;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

/**
 * CreateSaleFromExternalOrder
 *
 * Job encargado de transformar una `ExternalOrder` en una `Sale` interna,
 * persistir los `SaleItem` correspondientes, generar el OTP y disparar el
 * evento `ExternalOrderAccepted`.
 *
 * Buenas prácticas implementadas:
 * - Documentación exhaustiva y trazabilidad mediante logs en cada paso
 * - Transacción para garantizar atomicidad al crear venta y items
 * - Reintentos configurados y backoff para resiliencia
 *
 * @package App\Jobs
 */
class CreateSaleFromExternalOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Número de intentos del job
     * @var int
     */
    public int $tries = 3;

    /**
     * Tiempo de backoff entre reintentos (segundos)
     * @var int
     */
    public int $backoff = 60;

    /**
     * Orden externa a transformar en venta
     *
     * @var ExternalOrder
     */
    public ExternalOrder $order;

    /**
     * CreateSaleFromExternalOrder constructor.
     *
     * @param ExternalOrder $order Orden externa cargada (preferiblemente fresh)
     */
    public function __construct(ExternalOrder $order)
    {
        Log::debug('CreateSaleFromExternalOrder::__construct invoked', [
            'order_id' => $order->id,
            'external_order_id' => $order->external_order_id,
        ]);
        $this->order = $order;
    }

    /**
     * Ejecuta la conversión de ExternalOrder -> Sale
     *
     * Flujo:
     * 1. Crear `Sale` con datos del pedido
     * 2. Crear `SaleItem` para cada item de la orden
     * 3. Generar OTP mediante `ExternalOrder::generateOtp()`
     * 4. Actualizar estado de la orden a `PREPARATION`
     * 5. Disparar evento `ExternalOrderAccepted` con el OTP
     *
     * Se registran logs en cada paso para facilitar debugging en producción.
     *
     * @return void
     * @throws \Throwable Re-lanza excepciones para que el sistema de colas las maneje
     */
    public function handle(): void
    {
        Log::info('CreateSaleFromExternalOrder: starting job', ['external_order_id' => $this->order->id]);

        try {
            DB::transaction(function () {
                Log::debug('CreateSaleFromExternalOrder: creating sale record', ['external_order_id' => $this->order->id]);

                // Crear Sale
                $sale = Sale::create([
                    'external_order_id' => $this->order->id,
                    'team_id' => $this->order->team_id,
                    'customer_name' => $this->order->customer_name,
                    'customer_phone' => $this->order->customer_phone,
                    'customer_email' => $this->order->customer_email,
                    'customer_address' => $this->order->customer_address,
                    'total' => $this->order->estimated_total,
                    'payment_method' => $this->order->payment_method,
                    'status' => 'PENDING',
                ]);

                Log::info('CreateSaleFromExternalOrder: sale created', ['sale_id' => $sale->id, 'external_order_id' => $this->order->id]);

                // Crear SaleItems
                $itemsCount = 0;
                foreach ($this->order->items as $item) {
                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $item->product_id,
                        'name' => $item->name,
                        'sku' => $item->sku,
                        'quantity' => $item->qty,
                        'price' => $item->price,
                        'subtotal' => $item->qty * $item->price,
                    ]);
                    $itemsCount++;
                }

                Log::debug('CreateSaleFromExternalOrder: sale items created', ['sale_id' => $sale->id, 'items_count' => $itemsCount]);

                // Generar OTP
                Log::debug('CreateSaleFromExternalOrder: generating otp', ['external_order_id' => $this->order->id]);
                $otp = $this->order->generateOtp();
                Log::info('CreateSaleFromExternalOrder: otp generated', ['external_order_id' => $this->order->id, 'otp' => $otp]);

                // Actualizar estado
                $this->order->update([
                    'status' => 'PREPARATION',
                ]);
                Log::info('CreateSaleFromExternalOrder: external order status updated', ['external_order_id' => $this->order->id, 'status' => 'PREPARATION']);

                // Disparar evento
                ExternalOrderAccepted::dispatch($this->order, $otp);
                Log::info('CreateSaleFromExternalOrder: ExternalOrderAccepted event dispatched', ['external_order_id' => $this->order->id]);
            });

            Log::info('CreateSaleFromExternalOrder: job completed successfully', ['external_order_id' => $this->order->id]);
        } catch (\Throwable $e) {
            Log::error('CreateSaleFromExternalOrder: job failed', [
                'external_order_id' => $this->order->id,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Re-lanzar para que el manejador de colas aplique reintentos/backoff
            throw $e;
        }
    }

    /**
     * Maneja fallos del job (incluyendo desserialización y reintentos fallidos)
     *
     * Se invoca cuando el job se descarta o agota reintentos.
     *
     * @param \Throwable $exception Excepción que causó el fallo
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        Log::critical('CreateSaleFromExternalOrder: job permanently failed', [
            'order_id' => $this->order->id ?? 'unknown',
            'external_order_id' => $this->order->external_order_id ?? 'unknown',
            'exception' => $exception::class,
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
