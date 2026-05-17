<?php

namespace App\Mail\Transport;

use Illuminate\Support\Facades\Http;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\MessageConverter;

class BrevoTransport extends AbstractTransport
{
    public function __construct(private string $apiKey) {}

    protected function doSend(SentMessage $message): void
    {
        $email = MessageConverter::toEmail($message->getOriginalMessage());

        $from = $email->getFrom();
        $sender = [
            'email' => $from[0]->getAddress(),
            'name'  => $from[0]->getName() ?: 'DevHub',
        ];

        $to = [];
        foreach ($email->getTo() as $addr) {
            $to[] = ['email' => $addr->getAddress(), 'name' => $addr->getName() ?: ''];
        }

        $htmlBody = $email->getHtmlBody();
        $textBody = $email->getTextBody();

        $payload = [
            'sender'      => $sender,
            'to'          => $to,
            'subject'     => $email->getSubject(),
            'htmlContent' => $htmlBody ?: nl2br(htmlspecialchars((string) $textBody)),
        ];

        if ($textBody) {
            $payload['textContent'] = $textBody;
        }

        $response = Http::withHeaders(['api-key' => $this->apiKey])
            ->post('https://api.brevo.com/v3/smtp/email', $payload);

        if (!$response->successful()) {
            throw new \RuntimeException(
                'Brevo API error: ' . $response->status() . ' — ' . $response->body()
            );
        }
    }

    public function __toString(): string
    {
        return 'brevo';
    }
}
