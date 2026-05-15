<?php

namespace App\Mail;

use App\Models\Utilisateur;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BienvenueDevHub extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Utilisateur $utilisateur) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bienvenue sur DevHub ' . $this->utilisateur->prenom . ' !',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.bienvenue');
    }
}