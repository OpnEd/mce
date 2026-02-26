# Guía de Prueba: ExternalOrderActionService en Tinker

## Inicio de Tinker

```bash
php artisan tinker
```

## 1. Obtener una orden externa de prueba

```php
// Obtener la orden de prueba (ORDER-TEST-0070, order_id: 56)
$order = App\Models\Api\ExternalOrder::find(56);
$order;
```

**Esperado:**
- Debe mostrar la orden con status `CLAIMED` o `NOTIFIED`
- Debe tener `external_order_id`: `ORDER-TEST-0070`

---

## 2. Obtener el servicio del contenedor

```php
$service = app(App\Services\ExternalOrderActionService::class);
$service::class;
```

**Esperado:**
- Debe retornar `App\Services\ExternalOrderActionService`

---

## 3. Prueba: `takeOrder()` - Tomar una orden

```php
// Primero, obtener una orden en estado NOTIFIED (disponible)
$availableOrder = App\Models\Api\ExternalOrder::where('status', 'NOTIFIED')->first();

if ($availableOrder) {
    $teamId = 1; // o el ID de tu equipo de prueba
    $result = $service->takeOrder($availableOrder, $teamId);
    
    echo "Resultado takeOrder: " . ($result ? 'SUCCESS' : 'FAILED') . "\n";
    $availableOrder->refresh();
    echo "Nuevo status: " . $availableOrder->status . "\n";
    echo "Team ID asignado: " . $availableOrder->team_id . "\n";
} else {
    echo "No hay órdenes disponibles (NOTIFIED)\n";
}
```

**Esperado:**
- `takeOrder()` retorna `true`
- Status cambia a `CLAIMED`
- `team_id` se asigna correctamente
- Logs en `storage/logs/laravel.log`:
  - `ExternalOrderActionService::takeOrder - iniciando`
  - `ExternalOrderActionService::takeOrder - orden asignada exitosamente`

---

## 4. Prueba: `registerSale()` - Registrar venta y encolar job

```php
// Usar la orden que tomamos
$order = App\Models\Api\ExternalOrder::find(56);

// Registrar venta (encola job)
$service->registerSale($order);

echo "Job encolado para orden: " . $order->external_order_id . "\n";

// Verificar que el job está en la tabla de jobs (queue database)
$jobs = DB::table('jobs')->where('payload', 'like', '%ORDER-TEST-0070%')->get();
echo "Jobs encolados: " . $jobs->count() . "\n";
$jobs->first();
```

**Esperado:**
- Logs en `storage/logs/laravel.log`:
  - `ExternalOrderActionService::registerSale - iniciando`
  - `ExternalOrderActionService::registerSale - job encolado exitosamente`
  - `queue: "database"`
- Tabla `jobs` contiene una fila con el job `CreateSaleFromExternalOrder`

---

## 5. Prueba: `dispatchOrder()` - Marcar en tránsito

```php
$order = App\Models\Api\ExternalOrder::find(56);

// Primero, cambiar status a PREPARATION manualmente (simulando que CreateSaleFromExternalOrder fue ejecutado)
$order->update(['status' => 'PREPARATION', 'otp_code' => '1234']);

// Ahora despachar
try {
    $service->dispatchOrder($order);
    
    $order->refresh();
    echo "Status después de dispatch: " . $order->status . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

**Esperado:**
- Status cambia de `PREPARATION` a `IN_TRANSIT`
- Evento `ExternalOrderDispatched` se dispara
- Logs:
  - `ExternalOrderActionService::dispatchOrder - iniciando`
  - `ExternalOrderActionService::dispatchOrder - orden marcada en tránsito`

---

## 6. Prueba: `validateOtp()` - Validar código OTP

```php
$order = App\Models\Api\ExternalOrder::find(56);

// El OTP debería ser '1234' (del paso anterior)
$otpInput = '1234';

$isValid = $service->validateOtp($order, $otpInput);

echo "OTP válido: " . ($isValid ? 'YES' : 'NO') . "\n";

$order->refresh();
echo "Status después de validar OTP: " . $order->status . "\n";
```

**Esperado:**
- `validateOtp()` retorna `true`
- Status cambia a `DELIVERED_VERIFIED`
- Evento `ExternalOrderDelivered` se dispara
- Logs:
  - `ExternalOrderActionService::validateOtp - OTP válido`
  - `ExternalOrderActionService::validateOtp - orden confirmada como entregada`

---

## 7. Prueba: `rejectOrder()` - Rechazar una orden

```php
// Resetear la orden a estado inicial para rechazarla
$order = App\Models\Api\ExternalOrder::find(56);
$order->update([
    'status' => 'CLAIMED',
    'team_id' => 1,
    'claimed_at' => now(),
    'claimed_by' => 10,
    'otp_code' => null
]);

$teamId = 1;

try {
    $service->rejectOrder($order, $teamId);
    
    $order->refresh();
    echo "Status después de rechazar: " . $order->status . "\n";
    echo "Team ID después de rechazar: " . $order->team_id . "\n";
    
    // Verificar que el job de rechazo se encoló
    $rejectedJobs = DB::table('jobs')
        ->where('payload', 'like', '%RejectedExternalOrderJob%')
        ->count();
    echo "Jobs de rechazo encolados: " . $rejectedJobs . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
```

**Esperado:**
- Status cambia de `CLAIMED` a `NOTIFIED`
- `team_id` se limpia (null)
- Job `RejectedExternalOrderJob` se encola
- Logs:
  - `ExternalOrderActionService::rejectOrder - iniciando`
  - `ExternalOrderActionService::rejectOrder - orden rechazada y devuelta a candidatos`

---

## 8. Prueba: Ciclo completo simulado

```php
// Script completo que simula un flujo completo

$order = App\Models\Api\ExternalOrder::find(56);
$teamId = 1;
$service = app(App\Services\ExternalOrderActionService::class);

echo "=== CICLO COMPLETO DE PRUEBA ===\n\n";

// Reset orden
$order->update([
    'status' => 'NOTIFIED',
    'team_id' => null,
    'claimed_at' => null,
    'claimed_by' => null,
    'otp_code' => null
]);

echo "1. Estado inicial: " . $order->status . "\n";

// PASO 1: Tomar orden
$taken = $service->takeOrder($order, $teamId);
$order->refresh();
echo "2. Después de takeOrder: " . $order->status . " (success: " . ($taken ? 'YES' : 'NO') . ")\n";

// PASO 2: Registrar venta
$service->registerSale($order);
echo "3. Después de registerSale: Job encolado\n";

// PASO 3: Simular creación de venta (cambiar status y OTP)
$order->update(['status' => 'PREPARATION', 'otp_code' => '9999']);
echo "4. Simulando job ejecutado: status = PREPARATION, otp_code = 9999\n";

// PASO 4: Despachar
$service->dispatchOrder($order);
$order->refresh();
echo "5. Después de dispatchOrder: " . $order->status . "\n";

// PASO 5: Validar OTP
$isValid = $service->validateOtp($order, '9999');
$order->refresh();
echo "6. Después de validateOtp: " . $order->status . " (valid: " . ($isValid ? 'YES' : 'NO') . ")\n";

echo "\n=== CICLO COMPLETADO ===\n";
```

---

## 9. Ver los logs generados

```bash
# En otra terminal
tail -f storage/logs/laravel.log | grep -E "(ExternalOrderActionService|handleRegisterSale)"
```

O en Tinker:

```php
// Últimas 20 líneas del log
exec('tail -20 storage/logs/laravel.log', $output);
foreach ($output as $line) {
    echo $line . "\n";
}
```

---

## Checklist de Validación

- [ ] `takeOrder()`: Order status cambia a CLAIMED
- [ ] `registerSale()`: Job encolado en tabla `jobs`
- [ ] `dispatchOrder()`: Status cambia a IN_TRANSIT
- [ ] `validateOtp()`: Status cambia a DELIVERED_VERIFIED
- [ ] `rejectOrder()`: Status vuelve a NOTIFIED, team_id limpio
- [ ] Logs del servicio aparecen en `laravel.log`
- [ ] Logs de handlers (`handleRegisterSale`) aparecen en `laravel.log`
- [ ] Eventos se disparan correctamente

---

## Comandos rápidos (copy-paste)

```php
// Obtener orden y servicio
$order = App\Models\Api\ExternalOrder::find(56);
$service = app(App\Services\ExternalOrderActionService::class);

// Ver estado actual
$order->only(['id', 'external_order_id', 'status', 'team_id', 'otp_code']);

// Resetear orden a estado inicial
$order->update(['status' => 'NOTIFIED', 'team_id' => null, 'claimed_at' => null, 'claimed_by' => null, 'otp_code' => null]);

// Probar takeOrder
$service->takeOrder($order, 1);

// Ver jobs encolados
DB::table('jobs')->count();

// Ver últimas 5 líneas del log
exec('tail -5 storage/logs/laravel.log', $output);
implode("\n", $output);
```
