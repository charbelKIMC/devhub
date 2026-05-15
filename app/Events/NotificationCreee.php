<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationCreee implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('notifications.' . $this->notification->destinataire_id);
    }

    public function broadcastWith()
    {
        return [
            'id'      => $this->notification->id,
            'type'    => $this->notification->type,
            'contenu' => $this->notification->contenu,
            'lien'    => $this->notification->lien,
        ];
    }

    public function broadcastAs()
    {
        return 'notification.creee';
    }
}