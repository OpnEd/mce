<?php

namespace App\Notifications;

use App\Models\Api\ExternalOrder;
use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

/**
 * ExternalOrderRejectedNotification
 *
 * Notificación que informa a los equipos candidatos que una orden que fue rechazada
 * está disponible nuevamente para ser tomada.
 *
 * Se almacena en la base de datos y puede ser consultada a través de Filament.
 *
 * @package App\Notifications
 * @author Desarrollo
 */
class ExternalOrderRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Orden externa que fue rechazada
     *
     * @var ExternalOrder
     */
    protected ExternalOrder $order;

    /**
     * Equipo que la había tomado y la rechazó
     *
     * @var Team
     */
    protected Team $rejectedByTeam;

    /**
     * Distancia en metros desde el equipo candidato a la ubicación de la orden
     *
     * @var int
     */
    protected int $distanceMeters;

    /**
     * Constructor de la notificación
     *
     * @param ExternalOrder $order Orden rechazada
     * @param Team $rejectedByTeam Equipo que rechazó la orden
     * @param int $distanceMeters Distancia en metros
     */
    public function __construct(ExternalOrder $order, Team $rejectedByTeam, int $distanceMeters)
    {
        $this->order = $order;
        $this->rejectedByTeam = $rejectedByTeam;
        $this->distanceMeters = $distanceMeters;
    }

    /**
     * Canales por los cuales se envía la notificación
     *
     * @param object $notifiable Usuario que recibe la notificación
     * @return array
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Obtiene la representación de la notificación en base de datos
     *
     * @param object $notifiable Usuario que recibe la notificación
     * @return DatabaseMessage
     */
    public function toDatabase(object $notifiable): DatabaseMessage
    {
        return new DatabaseMessage([
            'order_id' => $this->order->id,
            'external_order_id' => $this->order->external_order_id,
            'customer_name' => $this->order->customer_name,
            'customer_phone' => $this->order->customer_phone,
            'customer_address' => $this->order->customer_address,
            'distance_m' => $this->distanceMeters,
            'distance_km' => round($this->distanceMeters / 1000, 2),
            'rejected_by_team' => $this->rejectedByTeam->name,
            'items_count' => $this->order->items->count(),
            'estimated_total' => $this->order->estimated_total,
            'message' => "¡La orden #{$this->order->external_order_id} está disponible de nuevo! " .
                        "Un equipo la rechazó. Está a {$this->getDistanceLabel()} de ti.",
            'title' => "Orden disponible: {$this->order->external_order_id}",
        ]);
    }

    /**
     * Obtiene la etiqueta de distancia en formato legible
     *
     * @return string
     */
    protected function getDistanceLabel(): string
    {
        if ($this->distanceMeters < 1000) {
            return "{$this->distanceMeters}m";
        }

        return round($this->distanceMeters / 1000, 1) . "km";
    }
}
