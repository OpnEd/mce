<?php

namespace App\Filament\Resources\Api\ExternalOrderResource\Pages;

use App\Filament\Resources\Api\ExternalOrderResource;
use App\Models\Api\ExternalOrder;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;

class ValidateOtp extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = ExternalOrderResource::class;

    protected static string $view = 'filament.resources.external-order-resource.pages.validate-otp';
    protected static ?string $title = 'Validar Código de Entrega';
    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    public ExternalOrder $order;
    public string $otpInput = '';
    public string $orderNumber = '';
    public ?string $customerName = null;

    public function mount(ExternalOrder $order): void
    {
        $this->order = $order;
        $this->orderNumber = $order->external_order_id;
        $this->customerName = $order->customer_name;

        // Solo accesible si está EN_TRANSIT
        if ($order->status !== 'IN_TRANSIT') {
            Notification::make()
                ->title('Orden no disponible')
                ->body('Esta orden no está en estado "En camino".')
                ->warning()
                ->send();

            $this->redirect('/');
        }
    }

    public function validateCode(): void
    {
        if (strlen($this->otpInput) !== 4 || !is_numeric($this->otpInput)) {
            Notification::make()
                ->title('Código inválido')
                ->body('Ingresa 4 dígitos.')
                ->warning()
                ->send();

            return;
        }

        if ($this->order->verifyOtp($this->otpInput)) {
            // Disparar evento
            \App\Events\ExternalOrderDelivered::dispatch($this->order);

            Notification::make()
                ->title('¡Entrega confirmada!')
                ->body('El código fue validado correctamente.')
                ->success()
                ->send();

            $this->redirect('/'); // O a página de éxito

        } else {
            Notification::make()
                ->title('Código incorrecto')
                ->body('El código no coincide. Intenta de nuevo.')
                ->danger()
                ->send();

            $this->otpInput = '';
        }
    }
}
