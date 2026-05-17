<?php

namespace App\Mail\Transport;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class BrevoTransport extends AbstractTransport
{
    public function __construct(private string $apiKey)
    {
        parent::__construct();
    }

    protected function doSend(SentMessage $message): void
    {
        if (empty($this->apiKey)) {
            \Log::error('Brevo: BREVO_API_KEY est vide — email non envoyé.');
            return;
        }

        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $fromList  = $email->getFrom();
        $fromEmail = !empty($fromList) ? $fromList[0]->getAddress() : config('mail.from.address');
        $fromName  = (!empty($fromList) && $fromList[0]->getName()) ? $fromList[0]->getName() : config('mail.from.name', 'DevHub');

        $to = [];
        foreach ($email->getTo() as $addr) {
            $addrEmail = $addr->getAddress();
            $to[] = ['email' => $addrEmail, 'name' => $addr->getName() ?: $addrEmail];
        }

        $htmlBody = $email->getHtmlBody();
        $textBody = $email->getTextBody();

        $payload = [
            'sender'      => ['email' => $fromEmail, 'name' => $fromName],
            'to'          => $to,
            'subject'     => $email->getSubject() ?: '(sans objet)',
            'htmlContent' => $htmlBody ?: nl2br(htmlspecialchars((string) $textBody)),
        ];

        if ($textBody) {
            $payload['textContent'] = $textBody;
        }

        \Log::info('Brevo: tentative envoi', [
            'to'      => array_column($to, 'email'),
            'subject' => $payload['subject'],
            'from'    => $fromEmail,
        ]);

        $response = Http::withHeaders([
            'api-key'      => $this->apiKey,
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', $payload);

        if ($response->successful()) {
            \Log::info('Brevo: email envoyé', ['messageId' => $response->json('messageId')]);
        } else {
            \Log::error('Brevo: erreur API', ['status' => $response->status(), 'body' => $response->body()]);
            throw new \RuntimeException('Brevo API ' . $response->status() . ': ' . $response->body());
        }
    }

    public function __toString(): string
    {
        return 'brevo';
    }
}
