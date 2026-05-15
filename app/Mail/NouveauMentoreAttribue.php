<?php
namespace App\Mail;
use App\Models\Utilisateur;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NouveauMentoreAttribue extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Utilisateur $mentor,
        public Utilisateur $developpeur
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Nouveau mentoré attribué – DevHub');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.nouveau-mentore-attribue');
    }
}
