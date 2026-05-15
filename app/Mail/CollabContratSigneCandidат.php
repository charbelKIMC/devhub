<?php

namespace App\Mail;

use App\Models\Publication;
use App\Models\Utilisateur;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CollabContratSigneCandidат extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Utilisateur $candidat,
        public Publication $pub,
        public string $cheminPdf
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Contrat signé — Bienvenue dans l\'équipe ! – DevHub');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.collab-contrat-signe-candidat');
    }

    public function attachments(): array
    {
        return [
            Attachment::fromStorageDisk('public', $this->cheminPdf)
                ->as('contrat-confidentialite.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
