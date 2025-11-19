<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Brevo
{
    public static function send(
        string $to,
        string $subject,
        string $html,
        string $fromEmail,
        string $fromName,
        ?string $text = null,
        array $tags = ['newsletter']
    ): bool {
        $apiKey = config('services.brevo.key'); // BREVO_API_KEY

        $payload = [
            'sender'      => ['email' => $fromEmail, 'name' => $fromName],
            'to'          => [['email' => $to]],
            'subject'     => $subject,
            'htmlContent' => $html,
            // versión texto plano
            'textContent' => $text ?? strip_tags($html),
            // cabeceras útiles
            'headers'     => [
                // List-Unsubscribe con mailto y URL
                'List-Unsubscribe' => '<mailto:bajas@apme.bo>, <https://apme.bo/newsletter/unsubscribe>',
            ],
            // agrupa/etiqueta envíos
            'tags'        => $tags,
        ];

        $resp = Http::withHeaders([
            'api-key'      => $apiKey,
            'accept'       => 'application/json',
            'content-type' => 'application/json',
        ])->post('https://api.brevo.com/v3/smtp/email', $payload);

        return $resp->successful();
    }
}
