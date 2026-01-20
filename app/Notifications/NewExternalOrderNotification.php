<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Api\ExternalOrder;
use App\Models\Team;
use App\Models\User;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Notifications\Actions\Action;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class NewExternalOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public ExternalOrder $order;
    public Team $team;
    public int $distanceMeters;

    /**
     * Create a new notification instance.
     */
    public function __construct(ExternalOrder $order, Team $team, int $distanceMeters = 0)
    {
        $this->order = $order;
        $this->team = $team;
        $this->distanceMeters = $distanceMeters;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */

    public function toDatabase(User $notifiable): array
    {
        try {
            $url = route('filament.admin.resources.api.external-orders.view', [
                'tenant' => $this->team,
                'record' => $this->order,
            ]);
        } catch (\Throwable $e) {
            Log::error('NewExternalOrderNotification: Error generando URL', [
                'error' => $e->getMessage(),
                'team_id' => $this->team->id ?? 'null',
            ]);
            $url = '#'; // Enlace de respaldo para no romper la notificación
        }

        // 1. Construimos la notificación usando el formato nativo de Filament
        $notification = FilamentNotification::make()
            ->title('Nueva orden disponible cerca de tu droguería')
            ->body("Orden {$this->order->external_order_id} a " . round($this->distanceMeters) . " m.")
            ->success()
            ->actions([
                Action::make('view')
                    ->label('Revisar orden')
                    ->url($url)
                    ->button(),
            ]);

        // 2. Combinamos con los datos personalizados para mantener compatibilidad con tu lógica de negocio
        // y asegurar que el check de duplicados 'data->order_id' del Job funcione.
        return array_merge($notification->getDatabaseMessage(), [
            'external_order_id' => $this->order->external_order_id,
            'order_id' => $this->order->id, // Descomentado y agregado para evitar duplicados
            'team_id' => $this->team->id,
            'distance_m' => $this->distanceMeters,
            'customer' => [
                'name' => $this->order->customer_name,
                'phone' => $this->order->customer_phone,
                'address' => $this->order->customer_address,
            ],
            'items_count' => $this->order->items()->count(),
            'meta' => [
                'notes' => $this->order->notes,
                'notify_radius_m' => $this->order->notify_radius_m,
            ],
            'distance_km'        => round($this->distanceMeters / 1000, 2),
        ]);
    }
}
