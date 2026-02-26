<?php

namespace App\Filament\Resources\Api\ExternalOrderResource\Pages;

use App\Filament\Resources\Api\ExternalOrderResource;
use App\Services\ExternalOrderActionService;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * ViewExternalOrder
 *
 * Página de Filament para visualizar detalles de una orden externa y gestionar
 * transiciones de estado mediante acciones interactivas.
 *
 * Funcionalidades:
 * - Tomar orden disponible (CLAIMED)
 * - Registrar venta y generar OTP (PREPARATION)
 * - Despachar orden en tránsito (IN_TRANSIT)
 * - Validar OTP y confirmar entrega (DELIVERED_VERIFIED)
 * - Rechazar orden y devolverla a candidatos (NOTIFIED)
 *
 * Control de concurrencia:
 * - Pessimistic locking en operaciones críticas
 * - Validación de permisos por equipo
 * - Estados de orden bien definidos
 *
 * @package App\Filament\Resources\Api\ExternalOrderResource\Pages
 * @author Desarrollo
 */
class ViewExternalOrder extends ViewRecord
{
    protected static string $resource = ExternalOrderResource::class;

    /**
     * Inyección de dependencia del servicio de acciones
     *
     * @var ExternalOrderActionService|null
     */
    private ?ExternalOrderActionService $orderActionService = null;

    /**
     * Obtiene la instancia del servicio de acciones (lazy loading)
     *
     * @return ExternalOrderActionService
     */
    private function getOrderActionService(): ExternalOrderActionService
    {
        return $this->orderActionService ??= app(ExternalOrderActionService::class);
    }

    /**
     * Hook de montaje: validaciones y limpiezas iniciales
     *
     * 1. Marca notificaciones no leídas como leídas
     * 2. Valida que la orden no esté asignada a otro equipo
     * 3. Redirige si no tiene autorización
     *
     * @param mixed $record ID de la orden a cargar
     * @return void
     */
    public function mount($record): void
    {
        parent::mount($record);

        // Marcar como leídas las notificaciones sobre esta orden
        Auth::user()->unreadNotifications()
            ->where('data->order_id', $this->record->id)
            ->get()
            ->markAsRead();

        // Obtener ID del equipo actual (tenant)
        $userTeamId = Filament::getTenant()->id;

        // Validar que la orden no está asignada a otro equipo
        if ($this->record->team_id && $this->record->team_id !== $userTeamId) {
            Notification::make()
                ->title('Orden ya asignada')
                ->body('Esta orden ya fue asignada a otro equipo.')
                ->danger()
                ->send();

            $this->redirect(ExternalOrderResource::getUrl());
            return;
        }
    }

    /**
     * Construye los botones de acción según el estado de la orden
     *
     * Lógica de transiciones:
     * 1. Si no está tomada: botón "Tomar Orden"
     * 2. Si CLAIMED: botones "Registrar Venta" y "Rechazar"
     * 3. Si PREPARATION: botón "Marcar en Camino"
     * 4. Si IN_TRANSIT: botón "Validar Código"
     * 5. Si DELIVERED_VERIFIED: botón informativo con redirección
     *
     * @return array Array de acciones disponibles
     */
    protected function getHeaderActions(): array
    {
        // Determinar estado actual de la orden
        $isDisponible = $this->record->team_id === null && $this->record->status === 'NOTIFIED';
        $isTakenByUserTeam = $this->record->team_id === Filament::getTenant()->id;
        $isInPreparation = $this->record->status === 'PREPARATION';
        $isInTransit = $this->record->status === 'IN_TRANSIT';
        $isClaimedByUs = $isTakenByUserTeam && $this->record->status === 'CLAIMED';
        $isDeliveredVerified = $this->record->status === 'DELIVERED_VERIFIED';

        // Habilitaciones por acción
        $canTake = $isDisponible;
        $canRegister = $isClaimedByUs;
        $canReject = $isClaimedByUs;
        $canDispatch = $isInPreparation;
        $canValidate = $isInTransit;
        $canDelivered = $isDeliveredVerified;

        // Construir acciones usando los builders existentes y sobreescribiendo estado/color
        $takeAction = $this->buildTakeOrderAction()
            ->disabled(!$canTake)
            ->color($canTake ? 'success' : 'secondary')
            ->tooltip($canTake ? 'Tomar esta orden' : 'No disponible: la orden no está disponible para tomar')
            ->visible(true);

        $registerAction = $this->buildRegisterSaleAction()
            ->disabled(!$canRegister)
            ->color($canRegister ? 'info' : 'secondary')
            ->tooltip($canRegister ? 'Registrar venta y generar OTP' : 'No disponible: la orden no está tomada por tu equipo')
            ->visible(true);

        $rejectAction = $this->buildRejectOrderAction()
            ->disabled(!$canReject)
            ->color($canReject ? 'danger' : 'secondary')
            ->tooltip($canReject ? 'Rechazar orden y devolverla a candidatos' : 'No disponible: solo el equipo que tomó la orden puede rechazarla')
            ->visible(true);

        $dispatchAction = $this->buildDispatchOrderAction()
            ->disabled(!$canDispatch)
            ->color($canDispatch ? 'warning' : 'secondary')
            ->tooltip($canDispatch ? 'Marcar orden en tránsito' : 'No disponible: requiere que la venta esté registrada y OTP generado')
            ->visible(true);

        $validateAction = $this->buildValidateOtpAction()
            ->disabled(!$canValidate)
            ->color($canValidate ? 'success' : 'secondary')
            ->tooltip($canValidate ? 'Validar el código OTP con el cliente' : 'No disponible: la orden no está en tránsito')
            ->visible(true);

        $deliveredAction = $this->buildDeliveredVerifiedAction()
            ->disabled(!$canDelivered)
            ->color($canDelivered ? 'success' : 'secondary')
            ->tooltip($canDelivered ? 'Orden entregada y verificada — ir al listado' : 'No disponible: la orden no ha sido verificada')
            ->visible(true);

        // Orden de botones en el header
        return [
            $takeAction,
            $registerAction,
            $rejectAction,
            $dispatchAction,
            $validateAction,
            $deliveredAction,
        ];
    }

    /**
     * Construye la acción "Tomar Orden"
     *
     * Muestra un modal con lista de items para que el equipo confirme disponibilidad.
     * Requiere que TODOS los items sean marcados como disponibles.
     *
     * @return Action
     */
    private function buildTakeOrderAction(): Action
    {
        return Action::make('take_order')
            ->label('Tomar Orden')
            ->modalIcon('heroicon-m-check-circle')
            ->color('success')
            ->form([
                Section::make('Items Disponibles')
                    ->description('Marca los items que tienes disponibles')
                    ->schema([
                        Repeater::make('items')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Producto')
                                    ->disabled()
                                    ->dehydrated()
                                    ->columnSpan(4),

                                TextInput::make('qty')
                                    ->label('Cantidad')
                                    ->type('number')
                                    ->disabled()
                                    ->columnSpan(1),

                                Checkbox::make('available')
                                    ->label('¿Disponible?')
                                    ->inline(false)
                                    ->columnSpan(1),
                            ])
                            ->columns(6)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->default(fn() => $this->record->items->map(fn($item) => [
                                'name' => $item->name,
                                'qty' => $item->qty,
                                'available' => false,
                            ])->toArray())
                            ->columnSpanFull(),
                    ]),
            ])
            ->action(fn(array $data) => $this->handleTakeOrder($data))
            ->requiresConfirmation()
            ->successRedirectUrl(ExternalOrderResource::getUrl())
            ->modalHeading('Confirmar asignación')
            ->modalDescription('¿Estás seguro de que quieres tomar esta orden?')
            ->modalWidth(MaxWidth::ThreeExtraLarge);
    }

    /**
     * Construye la acción "Registrar Venta"
     *
     * Inicia el proceso de creación de venta y generación de OTP.
     * Dispara un job asincrónico.
     *
     * @return Action
     */
    private function buildRegisterSaleAction(): Action
    {
        return Action::make('register_sale')
            ->label('Registrar Venta')
            ->icon('heroicon-m-document-text')
            ->color('info')
            ->action(fn() => $this->handleRegisterSale())
            ->requiresConfirmation()
            ->modalHeading('Registrar venta y generar OTP')
            ->modalDescription('Esto creará la venta y generará un código OTP para el cliente.');
    }

    /**
     * Construye la acción "Rechazar Orden"
     *
     * Devuelve la orden al estado NOTIFIED para que otros equipos candidatos
     * puedan tomarla. Notifica a los candidatos automáticamente.
     *
     * @return Action
     */
    private function buildRejectOrderAction(): Action
    {
        return Action::make('reject')
            ->label('Mejor no...')
            ->color('danger')
            ->action(fn() => $this->handleRejectOrder())
            ->requiresConfirmation()
            ->modalHeading('Rechazar orden')
            ->icon('heroicon-m-check-circle')
            ->tooltip('Poner de nuevo esta orden como disponible. Recuerda que solo tienes 5 minutos para hacerlo después de tomarla.');
    }

    /**
     * Construye la acción "Marcar en Camino"
     *
     * Cambia status a IN_TRANSIT y notifica al cliente.
     * Requiere que exista OTP previamente generado.
     *
     * @return Action
     */
    private function buildDispatchOrderAction(): Action
    {
        return Action::make('dispatch_order')
            ->label('Marcar en Camino')
            ->icon('heroicon-m-arrow-top-right-on-square')
            ->color('warning')
            ->action(fn() => $this->handleDispatchOrder())
            ->requiresConfirmation()
            ->modalHeading('Marcar orden en tránsito')
            ->modalDescription('Se notificará al cliente que su pedido está en camino con el código OTP.');
    }

    /**
     * Construye la acción "Validar OTP"
     *
     * Solicita código OTP al usuario y valida contra el almacenado.
     * Si es correcto, marca orden como DELIVERED_VERIFIED.
     *
     * @return Action
     */
    private function buildValidateOtpAction(): Action
    {
        return Action::make('validate_otp')
            ->label('Validar Código')
            ->icon('heroicon-m-lock-closed')
            ->color('success')
            ->modalWidth('sm')
            ->form([
                TextInput::make('otp_input')
                    ->label('Código de 4 dígitos')
                    ->placeholder('0000')
                    ->maxLength(4)
                    ->minLength(4)
                    ->numeric()
                    ->required()
                    ->autofocus(),
            ])
            ->action(fn(array $data) => $this->handleValidateOtp($data['otp_input']))
            ->requiresConfirmation();
    }

    /**
     * Construye la acción "Entregado y Verificado"
     *
     * Acción informativa que indica que la orden ya fue completada.
     * Redirige al listado de órdenes.
     *
     * @return Action
     */
    private function buildDeliveredVerifiedAction(): Action
    {
        return Action::make('delivered_verified')
            ->label('Entregado y Verificado')
            ->icon('heroicon-m-check-badge')
            ->color('success')
            ->action(fn() => $this->redirect(ExternalOrderResource::getUrl()))
            ->requiresConfirmation()
            ->modalHeading('Orden entregada y verificada')
            ->modalDescription('La orden ya ha sido entregada y verificada con éxito. Será direccionado a la lista de órdenes')
            ->tooltip('Esta orden ya ha sido entregada y verificada, serás direccionado a la lista.');
    }

    /**
     * Manejador: Tomar una orden
     *
     * Validaciones:
     * - Todos los items deben estar disponibles
     *
     * Flujo:
     * 1. Validar disponibilidad de items
     * 2. Llamar servicio para asignar orden
     * 3. Mostrar notificación
     * 4. Refrescar vista
     *
     * @param array $data Datos del formulario
     * @return void
     */
    private function handleTakeOrder(array $data): void
    {
        try {
            // Validar que TODOS los items están checked
            $items = $data['items'] ?? [];
            $allChecked = collect($items)->every(fn($item) => $item['available'] ?? false);

            if (!$allChecked) {
                Notification::make()
                    ->title('Todos los items deben estar disponibles')
                    ->body('Toma la orden solo si tienes TODOS los items disponibles.')
                    ->warning()
                    ->send();
                return;
            }

            $teamId = Filament::getTenant()->id;

            // Usar servicio para tomar orden
            $taken = $this->getOrderActionService()->takeOrder($this->record, $teamId);

            if (!$taken) {
                Notification::make()
                    ->title('Orden ya fue tomada')
                    ->body('Otro equipo tomó esta orden un instante antes que tú.')
                    ->warning()
                    ->send();
                $this->redirect(ExternalOrderResource::getUrl());
                return;
            }

            Notification::make()
                ->title('Orden asignada')
                ->body("Orden {$this->record->external_order_id} tomada exitosamente")
                ->success()
                ->send();

            // Refrescar vista
            $this->record->refresh();
            $this->mount($this->record->id);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('No se pudo asignar la orden: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Manejador: Registrar venta
     *
     * Encola job asincrónico que:
     * - Crea la venta
     * - Genera OTP
     * - Notifica al cliente
     *
     * Flujo de logs:
     * 1. DEBUG: inicio del handler con datos de orden
     * 2. DEBUG: obtención del servicio
     * 3. INFO: se encolará job CreateSaleFromExternalOrder
     * 4. DEBUG: notificación enviada al usuario
     * 5. ERROR: cualquier excepción durante el proceso
     *
     * @return void
     */
    private function handleRegisterSale(): void
    {
        $orderId = $this->record->id ?? 'unknown';
        $externalOrderId = $this->record->external_order_id ?? 'unknown';
        $teamId = Filament::getTenant()->id ?? 'unknown';
        $service = $this->getOrderActionService();

        try {
            // Log inicio del proceso
            Log::debug('handleRegisterSale: iniciando', [
                'order_id' => $orderId,
                'external_order_id' => $externalOrderId,
                'team_id' => $teamId,
                'status' => $this->record->status,
            ]);

            // Debug: obtener el servicio y verificar su tipo
            Log::debug('handleRegisterSale: servicio obtenido', [
                'service_class' => $service::class,
                'service_type' => gettype($service),
                'has_registerSale_method' => method_exists($service, 'registerSale'),
            ]);

            // Log ANTES de llamar al servicio
            Log::debug('handleRegisterSale: PRE-CALL a registerSale', [
                'order_id' => $orderId,
                'external_order_id' => $externalOrderId,
            ]);

            // Llamar al servicio para encolar el job
            $service->registerSale($this->record);

            // Log DESPUÉS de llamar al servicio
            Log::debug('handleRegisterSale: POST-CALL a registerSale exitoso', [
                'order_id' => $orderId,
                'external_order_id' => $externalOrderId,
            ]);

            // Log éxito después de encolar
            Log::info('handleRegisterSale: venta encolada exitosamente', [
                'order_id' => $orderId,
                'external_order_id' => $externalOrderId,
            ]);

            // Debug: solo en local o APP_DEBUG
            if (app()->isLocal() || config('app.debug')) {
                Log::debug('handleRegisterSale: notificación éxito al usuario', [
                    'user_id' => Auth::id(),
                ]);

                try {
                    Notification::make()
                        ->title('Debug: handleRegisterSale ejecutado')
                        ->body("Job encolado para orden {$externalOrderId}")
                        ->info()
                        ->send();
                } catch (\Throwable $e) {
                    Log::debug('handleRegisterSale: debug notification failed', ['err' => $e->getMessage()]);
                }
            }

            // Notificación de éxito al usuario
            Notification::make()
                ->title('Venta registrada')
                ->body('Se está procesando la venta y generando el código OTP...')
                ->success()
                ->send();

            // Refrescar registro desde BD
            $this->record->refresh();

            Log::debug('handleRegisterSale: registro refrescado', [
                'order_id' => $orderId,
                'new_status' => $this->record->status,
            ]);

        } catch (\Throwable $e) {
            // Log del error detallado
            Log::error('handleRegisterSale: EXCEPCIÓN capturada', [
                'order_id' => $orderId,
                'external_order_id' => $externalOrderId,
                'team_id' => $teamId,
                'exception' => $e::class,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Notificación de error al usuario
            Notification::make()
                ->title('Error')
                ->body('No se pudo registrar la venta: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Manejador: Despachar orden
     *
     * Marca la orden como en tránsito.
     * Valida que exista OTP antes de despachar.
     *
     * @return void
     */
    private function handleDispatchOrder(): void
    {
        try {
            $this->getOrderActionService()->dispatchOrder($this->record);

            Notification::make()
                ->title('Orden en camino')
                ->body('Se notificó al cliente que su pedido está en tránsito.')
                ->success()
                ->send();

            $this->record->refresh();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Manejador: Validar OTP
     *
     * Verifica código ingresado contra el almacenado.
     * Si es correcto, marca como DELIVERED_VERIFIED.
     * Si es incorrecto, permite reintentos.
     *
     * @param string $otpInput Código OTP ingresado
     * @return void
     */
    private function handleValidateOtp(string $otpInput): void
    {
        try {
            $isValid = $this->getOrderActionService()->validateOtp($this->record, $otpInput);

            if ($isValid) {
                Notification::make()
                    ->title('Código verificado')
                    ->body('Orden entregada y confirmada. ¡Gracias por usar nuestro servicio!')
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Código incorrecto')
                    ->body('El código no coincide. Intenta de nuevo.')
                    ->warning()
                    ->send();
            }

            $this->record->refresh();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Error validando el código: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Manejador: Rechazar orden
     *
     * Devuelve orden al estado NOTIFIED para que otros candidatos puedan tomarla.
     * Encola job para notificar a candidatos automáticamente.
     *
     * Validación:
     * - Solo el equipo que tomó la orden puede rechazarla
     *
     * @return void
     */
    private function handleRejectOrder(): void
    {
        try {
            $teamId = Filament::getTenant()->id;
            $this->getOrderActionService()->rejectOrder($this->record, $teamId);

            Notification::make()
                ->title('Orden rechazada')
                ->body("La orden {$this->record->external_order_id} ha sido devuelta. Otros equipos pueden tomarla.")
                ->success()
                ->send();

            $this->record->refresh();
            $this->mount($this->record->id);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('No se pudo rechazar la orden: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
