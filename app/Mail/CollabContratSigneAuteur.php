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

class CollabContratSigneAuteur extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Utilisateur $auteur,
        public Utilisateur $candidat,
        public Publication $pub,
        public string $cheminPdf
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Un collaborateur a signé le contrat – DevHub');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.collab-contrat-signe-auteur');
    }

    public function attachments(): array
    {
        return [
            Attachment::fromStorageDisk('public', $this->cheminPdf)
                ->as('contrat-confidentialite-' . \Illuminate\Support\Str::slug($this->candidat->prenom . '-' . $this->candidat->nom) . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
