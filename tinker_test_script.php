<?php
/**
 * Script de prueba para ExternalOrderActionService
 * Ejecutar con: php artisan tinker < tinker_test_script.php
 * O incluir en tinker manualmente
 */

echo "\n========================================\n";
echo "INICIANDO PRUEBAS: ExternalOrderActionService\n";
echo "========================================\n\n";

// Obtener orden y servicio
$order = App\Models\Api\ExternalOrder::find(56);
$service = app(App\Services\ExternalOrderActionService::class);

if (!$order) {
    echo "❌ ERROR: No se encontró la orden 56\n";
    exit(1);
}

echo "✅ Orden obtenida: " . $order->external_order_id . "\n";
echo "✅ Servicio obtenido: " . $service::class . "\n\n";

// ============================================================
// PRUEBA 1: ESTADO INICIAL
// ============================================================
echo "---- PRUEBA 1: ESTADO INICIAL ----\n";
echo "ID: " . $order->id . "\n";
echo "External ID: " . $order->external_order_id . "\n";
echo "Status: " . $order->status . "\n";
echo "Team ID: " . $order->team_id . "\n";
echo "OTP Code: " . ($order->otp_code ? '✅ Presente' : '❌ No presente') . "\n\n";

// ============================================================
// PRUEBA 2: RESETEAR A ESTADO INICIAL
// ============================================================
echo "---- PRUEBA 2: RESETEAR ORDEN ----\n";
$order->update([
    'status' => 'NOTIFIED',
    'team_id' => null,
    'claimed_at' => null,
    'claimed_by' => null,
    'otp_code' => null
]);
$order->refresh();
echo "✅ Orden reseteada a: " . $order->status . "\n\n";

// ============================================================
// PRUEBA 3: takeOrder()
// ============================================================
echo "---- PRUEBA 3: takeOrder() ----\n";
$teamId = 1;
try {
    $taken = $service->takeOrder($order, $teamId);
    $order->refresh();
    
    if ($taken && $order->status === 'CLAIMED') {
        echo "✅ takeOrder(): EXITOSO\n";
        echo "   Status: " . $order->status . "\n";
        echo "   Team ID: " . $order->team_id . "\n";
    } else {
        echo "❌ takeOrder(): FALLÓ\n";
        echo "   Resultado: " . ($taken ? 'true' : 'false') . "\n";
    }
} catch (Exception $e) {
    echo "❌ takeOrder() lanzó excepción: " . $e->getMessage() . "\n";
}
echo "\n";

// ============================================================
// PRUEBA 4: registerSale()
// ============================================================
echo "---- PRUEBA 4: registerSale() ----\n";
try {
    $service->registerSale($order);
    
    // Verificar que el job se encoló
    $jobCount = DB::table('jobs')
        ->where('payload', 'like', '%CreateSaleFromExternalOrder%')
        ->count();
    
    if ($jobCount > 0) {
        echo "✅ registerSale(): EXITOSO\n";
        echo "   Jobs encolados: " . $jobCount . "\n";
        
        // Mostrar detalles del job
        $job = DB::table('jobs')
            ->where('payload', 'like', '%CreateSaleFromExternalOrder%')
            ->latest()
            ->first();
        
        echo "   Queue: " . $job->queue . "\n";
        echo "   Attempts: " . $job->attempts . "\n";
    } else {
        echo "❌ registerSale(): No se encoló el job\n";
    }
} catch (Exception $e) {
    echo "❌ registerSale() lanzó excepción: " . $e->getMessage() . "\n";
}
echo "\n";

// ============================================================
// PRUEBA 5: dispatchOrder()
// ============================================================
echo "---- PRUEBA 5: dispatchOrder() ----\n";
try {
    // Primero, simular que el job CreateSaleFromExternalOrder fue ejecutado
    $order->update(['status' => 'PREPARATION', 'otp_code' => '1234']);
    $order->refresh();
    
    echo "   [SIMULACIÓN] Status: PREPARATION, OTP: 1234\n";
    
    $service->dispatchOrder($order);
    $order->refresh();
    
    if ($order->status === 'IN_TRANSIT') {
        echo "✅ dispatchOrder(): EXITOSO\n";
        echo "   Status: " . $order->status . "\n";
    } else {
        echo "❌ dispatchOrder(): FALLÓ\n";
        echo "   Status actual: " . $order->status . "\n";
    }
} catch (Exception $e) {
    echo "❌ dispatchOrder() lanzó excepción: " . $e->getMessage() . "\n";
}
echo "\n";

// ============================================================
// PRUEBA 6: validateOtp() - VÁLIDO
// ============================================================
echo "---- PRUEBA 6: validateOtp() - CÓDIGO CORRECTO ----\n";
try {
    $isValid = $service->validateOtp($order, '1234');
    $order->refresh();
    
    if ($isValid && $order->status === 'DELIVERED_VERIFIED') {
        echo "✅ validateOtp(): EXITOSO\n";
        echo "   OTP válido: YES\n";
        echo "   Status: " . $order->status . "\n";
    } else {
        echo "❌ validateOtp(): FALLÓ\n";
        echo "   OTP válido: " . ($isValid ? 'YES' : 'NO') . "\n";
        echo "   Status: " . $order->status . "\n";
    }
} catch (Exception $e) {
    echo "❌ validateOtp() lanzó excepción: " . $e->getMessage() . "\n";
}
echo "\n";

// ============================================================
// PRUEBA 7: validateOtp() - INVÁLIDO
// ============================================================
echo "---- PRUEBA 7: validateOtp() - CÓDIGO INCORRECTO ----\n";
// Resetear a IN_TRANSIT
$order->update(['status' => 'IN_TRANSIT']);
$order->refresh();

try {
    $isValid = $service->validateOtp($order, '9999');
    
    if (!$isValid) {
        echo "✅ validateOtp(): CORRECTO - Rechazó código inválido\n";
        echo "   OTP válido: NO\n";
        echo "   Status sin cambios: " . $order->status . "\n";
    } else {
        echo "❌ validateOtp(): ERROR - Aceptó código inválido\n";
    }
} catch (Exception $e) {
    echo "❌ validateOtp() lanzó excepción: " . $e->getMessage() . "\n";
}
echo "\n";

// ============================================================
// PRUEBA 8: rejectOrder()
// ============================================================
echo "---- PRUEBA 8: rejectOrder() ----\n";
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
    
    echo "   [PREPARACIÓN] Status: CLAIMED, Team: " . $order->team_id . "\n";
    
    $service->rejectOrder($order, $teamId);
    $order->refresh();
    
    // Verificar que el job de rechazo se encoló
    $rejectedJobCount = DB::table('jobs')
        ->where('payload', 'like', '%RejectedExternalOrderJob%')
        ->count();
    
    if ($order->status === 'NOTIFIED' && $order->team_id === null && $rejectedJobCount > 0) {
        echo "✅ rejectOrder(): EXITOSO\n";
        echo "   Status: " . $order->status . "\n";
        echo "   Team ID: " . ($order->team_id ?? 'NULL') . "\n";
        echo "   RejectedExternalOrderJob encolado: YES\n";
    } else {
        echo "❌ rejectOrder(): FALLÓ\n";
        echo "   Status: " . $order->status . "\n";
        echo "   Team ID: " . ($order->team_id ?? 'NULL') . "\n";
        echo "   Job encolado: " . ($rejectedJobCount > 0 ? 'YES' : 'NO') . "\n";
    }
} catch (Exception $e) {
    echo "❌ rejectOrder() lanzó excepción: " . $e->getMessage() . "\n";
}
echo "\n";

// ============================================================
// RESUMEN DE LOGS
// ============================================================
echo "---- RESUMEN: ÚLTIMOS LOGS ----\n";
exec('tail -30 storage/logs/laravel.log', $output);

echo "Últimas 30 líneas del log:\n";
echo str_repeat("=", 60) . "\n";
foreach ($output as $line) {
    // Filtrar solo líneas relevantes
    if (strpos($line, 'ExternalOrderActionService') !== false || 
        strpos($line, 'handleRegisterSale') !== false ||
        strpos($line, 'buildRegisterSaleAction') !== false) {
        echo $line . "\n";
    }
}
echo str_repeat("=", 60) . "\n\n";

// ============================================================
// RESUMEN FINAL
// ============================================================
echo "========================================\n";
echo "PRUEBAS COMPLETADAS\n";
echo "========================================\n";
echo "✅ Revisa storage/logs/laravel.log para detalles completos\n";
echo "✅ Revisa la tabla 'jobs' para ver los jobs encolados\n\n";
