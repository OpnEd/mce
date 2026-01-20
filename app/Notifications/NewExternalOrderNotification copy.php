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
        // 'database' se elimina porque la inserción se hace manualmente en el Job para incluir 'team_id'
        //return ['broadcast'];
        //return ['database', 'broadcast'];
        return ['database'];
    }

    /* public function broadcastOn(): array
    {
        return [new PrivateChannel('team.' . $this->team->id . '.notifications')];
    }

    public function broadcastWith(): array
    {
        return [
            'title' => 'Nueva orden disponible',
            'order_id' => $this->order->id,
            'external_order_id' => $this->order->external_order_id,
            'team_id' => $this->team->id,
        ];
    }

    public function broadcastAs(): string
    {
        return 'OrderNotification';
    } */

    /**
     * Get the mail representation of the notification.
     */

    public function toDatabase(User $notifiable): array
    {
        $url = route('filament.admin.resources.api.external-orders.view', $this->order->id);

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

    /* public function toBroadcast(User $notifiable): BroadcastMessage
    {
        //return new BroadcastMessage($this->toDatabase($notifiable));
        return FilamentNotification::make()
            ->title('Saved successfully')
            ->getBroadcastMessage();
    } */

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */

    // Optional: toArray() for broadcasting
   /*  public function toArray($notifiable)
    {
        return $this->toDatabase($notifiable);
    } */
}
