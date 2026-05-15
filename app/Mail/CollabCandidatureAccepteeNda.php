<?php

namespace App\Mail;

use App\Models\Publication;
use App\Models\Utilisateur;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CollabCandidatureAccepteeNda extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Utilisateur $candidat,
        public Publication $pub,
        public string $lienNda
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Candidature acceptée — Signez le contrat de confidentialité – DevHub');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.collab-candidature-acceptee-nda');
    }
}
