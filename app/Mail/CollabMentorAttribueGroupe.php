<?php

namespace App\Mail;

use App\Models\Conversation;
use App\Models\Publication;
use App\Models\Utilisateur;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CollabMentorAttribueGroupe extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Utilisateur $mentor,
        public Publication $pub,
        public Conversation $conversation
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Vous avez été attribué à un projet collaboratif – DevHub');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.collab-mentor-attribue-groupe');
    }
}
