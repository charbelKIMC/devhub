<?php

namespace App\Mail;

use App\Models\Utilisateur;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DemandeMentorAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Utilisateur $developpeur,
        public ?Utilisateur $mentorSouhaite
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle demande de mentor – DevHub',
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.demande-mentor-admin');
    }
}