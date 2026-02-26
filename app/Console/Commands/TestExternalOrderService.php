<?php

namespace App\Console\Commands;

use App\Models\Api\ExternalOrder;
use App\Services\ExternalOrderActionService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestExternalOrderService extends Command
{
    protected $signature = 'test:external-order-service';
    protected $description = 'Pruebas completas para ExternalOrderActionService';

    public function handle(): int
    {
        $this->info("\n========================================");
        $this->info("INICIANDO PRUEBAS: ExternalOrderActionService");
        $this->info("========================================\n");

        // Obtener orden y servicio
        $order = ExternalOrder::find(56);
        $service = app(ExternalOrderActionService::class);

        if (!$order) {
            $this->error("❌ ERROR: No se encontró la orden 56");
            return 1;
        }

        $this->info("✅ Orden obtenida: " . $order->external_order_id);
        $this->info("✅ Servicio obtenido: " . $service::class . "\n");

        // ============================================================
        // PRUEBA 1: ESTADO INICIAL
        // ============================================================
        $this->line("---- PRUEBA 1: ESTADO INICIAL ----");
        $this->info("ID: " . $order->id);
        $this->info("External ID: " . $order->external_order_id);
        $this->info("Status: " . $order->status);
        $this->info("Team ID: " . ($order->team_id ?? 'NULL'));
        $this->info("OTP Code: " . ($order->otp_code ? '✅ Presente' : '❌ No presente') . "\n");

        // ============================================================
        // PRUEBA 2: RESETEAR A ESTADO INICIAL
        // ============================================================
        $this->line("---- PRUEBA 2: RESETEAR ORDEN ----");
        $order->update([
            'status' => 'NOTIFIED',
            'team_id' => null,
            'claimed_at' => null,
            'claimed_by' => null,
            'otp_code' => null
        ]);
        $order->refresh();
        $this->info("✅ Orden reseteada a: " . $order->status . "\n");

        // ============================================================
        // PRUEBA 3: takeOrder()
        // ============================================================
        $this->line("---- PRUEBA 3: takeOrder() ----");
        $teamId = 1;
        try {
            $taken = $service->takeOrder($order, $teamId);
            $order->refresh();

            if ($taken && $order->status === 'CLAIMED') {
                $this->info("✅ takeOrder(): EXITOSO");
                $this->info("   Status: " . $order->status);
                $this->info("   Team ID: " . $order->team_id);
            } else {
                $this->error("❌ takeOrder(): FALLÓ");
                $this->error("   Resultado: " . ($taken ? 'true' : 'false'));
            }
        } catch (\Exception $e) {
            $this->error("❌ takeOrder() lanzó excepción: " . $e->getMessage());
        }
        $this->line("");

        // ============================================================
        // PRUEBA 4: registerSale()
        // ============================================================
        $this->line("---- PRUEBA 4: registerSale() ----");
        try {
            $service->registerSale($order);

            // Verificar que el job se encoló
            $jobCount = DB::table('jobs')
                ->where('payload', 'like', '%CreateSaleFromExternalOrder%')
                ->count();

            if ($jobCount > 0) {
                $this->info("✅ registerSale(): EXITOSO");
                $this->info("   Jobs encolados: " . $jobCount);

                // Mostrar detalles del job
                $job = DB::table('jobs')
                    ->where('payload', 'like', '%CreateSaleFromExternalOrder%')
                    ->latest()
                    ->first();

                $this->info("   Queue: " . $job->queue);
                $this->info("   Attempts: " . $job->attempts);
            } else {
                $this->error("❌ registerSale(): No se encoló el job");
            }
        } catch (\Exception $e) {
            $this->error("❌ registerSale() lanzó excepción: " . $e->getMessage());
        }
        $this->line("");

        // ============================================================
        // PRUEBA 5: dispatchOrder()
        // ============================================================
        $this->line("---- PRUEBA 5: dispatchOrder() ----");
        try {
            // Primero, simular que el job CreateSaleFromExternalOrder fue ejecutado
            $order->update(['status' => 'PREPARATION', 'otp_code' => '1234']);
            $order->refresh();

            $this->info("   [SIMULACIÓN] Status: PREPARATION, OTP: 1234");

            $service->dispatchOrder($order);
            $order->refresh();

            if ($order->status === 'IN_TRANSIT') {
                $this->info("✅ dispatchOrder(): EXITOSO");
                $this->info("   Status: " . $order->status);
            } else {
                $this->error("❌ dispatchOrder(): FALLÓ");
                $this->error("   Status actual: " . $order->status);
            }
        } catch (\Exception $e) {
            $this->error("❌ dispatchOrder() lanzó excepción: " . $e->getMessage());
        }
        $this->line("");

        // ============================================================
        // PRUEBA 6: validateOtp() - VÁLIDO
        // ============================================================
        $this->line("---- PRUEBA 6: validateOtp() - CÓDIGO CORRECTO ----");
        try {
            $isValid = $service->validateOtp($order, '1234');
            $order->refresh();

            if ($isValid && $order->status === 'DELIVERED_VERIFIED') {
                $this->info("✅ validateOtp(): EXITOSO");
                $this->info("   OTP válido: YES");
                $this->info("   Status: " . $order->status);
            } else {
                $this->error("❌ validateOtp(): FALLÓ");
                $this->error("   OTP válido: " . ($isValid ? 'YES' : 'NO'));
                $this->error("   Status: " . $order->status);
            }
        } catch (\Exception $e) {
            $this->error("❌ validateOtp() lanzó excepción: " . $e->getMessage());
        }
        $this->line("");

        // ============================================================
        // PRUEBA 7: validateOtp() - INVÁLIDO
        // ============================================================
        $this->line("---- PRUEBA 7: validateOtp() - CÓDIGO INCORRECTO ----");
        // Resetear a IN_TRANSIT
        $order->update(['status' => 'IN_TRANSIT']);
        $order->refresh();

        try {
            $isValid = $service->validateOtp($order, '9999');

            if (!$isValid) {
                $this->info("✅ validateOtp(): CORRECTO - Rechazó código inválido");
                $this->info("   OTP válido: NO");
                $this->info("   Status sin cambios: " . $order->status);
            } else {
                $this->error("❌ validateOtp(): ERROR - Aceptó código inválido");
            }
        } catch (\Exception $e) {
            $this->error("❌ validateOtp() lanzó excepción: " . $e->getMessage());
        }
        $this->line("");

        // ============================================================
        // PRUEBA 8: rejectOrder()
        // ============================================================
        $this->line("---- PRUEBA 8: rejectOrder() ----");
        try {
            // Resetear a CLAIMED para poder rechazar
            $order->update([
                'status' => 'CLAIMED',
                'team_id' => $teamId,
                'claimed_at' => now(),
                'claimed_by' => 10,
                'otp_code' => null
            ]);
            $order->refresh();

            $this->info("   [PREPARACIÓN] Status: CLAIMED, Team: " . $order->team_id);

            $service->rejectOrder($order, $teamId);
            $order->refresh();

            // Verificar que el job de rechazo se encoló
            $rejectedJobCount = DB::table('jobs')
                ->where('payload', 'like', '%RejectedExternalOrderJob%')
                ->count();

            if ($order->status === 'NOTIFIED' && $order->team_id === null && $rejectedJobCount > 0) {
                $this->info("✅ rejectOrder(): EXITOSO");
                $this->info("   Status: " . $order->status);
                $this->info("   Team ID: " . ($order->team_id ?? 'NULL'));
                $this->info("   RejectedExternalOrderJob encolado: YES");
            } else {
                $this->error("❌ rejectOrder(): FALLÓ");
                $this->error("   Status: " . $order->status);
                $this->error("   Team ID: " . ($order->team_id ?? 'NULL'));
                $this->error("   Job encolado: " . ($rejectedJobCount > 0 ? 'YES' : 'NO'));
            }
        } catch (\Exception $e) {
            $this->error("❌ rejectOrder() lanzó excepción: " . $e->getMessage());
        }
        $this->line("");

        // ============================================================
        // RESUMEN FINAL
        // ============================================================
        $this->info("========================================");
        $this->info("PRUEBAS COMPLETADAS");
        $this->info("========================================");
        $this->line("✅ Revisa storage/logs/laravel.log para detalles completos");
        $this->line("✅ Revisa la tabla 'jobs' para ver los jobs encolados\n");

        return 0;
    }
}
