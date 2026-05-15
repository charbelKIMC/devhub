<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageEnvoye implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('conversation.' . $this->message->conversation_id);
    }

    public function broadcastWith()
    {
        return [
            'id'             => $this->message->id,
            'contenu'        => $this->message->contenu,
            'expediteur_id'  => $this->message->expediteur_id,
            'date_envoi'     => $this->message->date_envoi->format('H:i'),
            'avatar'         => $this->message->expediteur->photo,
            'initiale'       => strtoupper(substr($this->message->expediteur->prenom, 0, 1)),
        ];
    }

    public function broadcastAs()
    {
        return 'message.envoye';
    }
}