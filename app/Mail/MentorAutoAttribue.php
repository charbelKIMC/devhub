<?php
namespace App\Mail;
use App\Models\Utilisateur;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MentorAutoAttribue extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Utilisateur $developpeur,
        public Utilisateur $mentor
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Un mentor vous a été attribué – DevHub');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.mentor-auto-attribue');
    }
}
