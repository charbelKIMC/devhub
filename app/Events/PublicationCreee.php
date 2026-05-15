<?php

namespace App\Events;

use App\Models\Publication;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PublicationCreee implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $publication;

    public function __construct(Publication $publication)
    {
        $this->publication = $publication;
    }

    public function broadcastOn()
    {
        // Canal public — tous les utilisateurs connectés voient les nouvelles publications
        return new Channel('feed.public');
    }

    public function broadcastWith()
    {
        return [
            'id'     => $this->publication->id,
            'titre'  => $this->publication->titre,
            'type'   => $this->publication->type,
            'auteur' => $this->publication->auteur->prenom . ' ' . $this->publication->auteur->nom,
        ];
    }

    public function broadcastAs()
    {
        return 'publication.creee';
    }
}