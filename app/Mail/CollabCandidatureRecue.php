<?php

namespace App\Mail;

use App\Models\Publication;
use App\Models\Utilisateur;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CollabCandidatureRecue extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Utilisateur $auteur,
        public Utilisateur $candidat,
        public Publication $pub
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Nouvelle candidature sur votre projet – DevHub');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.collab-candidature-recue');
    }
}
