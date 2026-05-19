<?php

namespace App\Mail;

use App\Models\Utilisateur;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminMentorContratSigne extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Utilisateur $mentor,
        public string $pdfContenu
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Nouveau contrat mentor signé – DevHub');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.admin-mentor-contrat-signe');
    }

    public function attachments(): array
    {
        $nom = \Illuminate\Support\Str::slug($this->mentor->prenom . '-' . $this->mentor->nom);
        return [
            Attachment::fromData(fn() => $this->pdfContenu, 'contrat-mentor-' . $nom . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
