<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otp,
        public string $prenom = 'Développeur'
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Votre code de réinitialisation – DevHub');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.otp-reset-password');
    }
}
