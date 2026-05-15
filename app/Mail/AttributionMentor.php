<?php
namespace App\Mail;
use App\Models\Utilisateur;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
 
class AttributionMentor extends Mailable
{
    use Queueable, SerializesModels;
 
    public function __construct(
        public Utilisateur $mentor,
        public Utilisateur $mentore
    ) {}
 
    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Nouveau mentoré attribué – DevHub');
    }
 
    public function content(): Content
    {
        return new Content(view: 'emails.attribution-mentor');
    }
}