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
        ?string $fromEmail = null,
        ?string $fromName  = null
    ): bool|string {
        $key  = config('services.brevo.key') ?? env('BREVO_API_KEY');
        $fromEmail = $fromEmail ?: (config('mail.from.address') ?? 'no-reply@example.com');
        $fromName  = $fromName  ?: (config('mail.from.name')    ?? config('app.name', 'App'));

        if (!$key) {
            Log::error('[BrevoApi] Falta BREVO_API_KEY');
            return 'BREVO_API_KEY missing';
        }

        $payload = [
            'sender'      => ['email' => $fromEmail, 'name' => $fromName],
            'to'          => [['email' => $to]],
            'subject'     => $subject,
            'htmlContent' => $html,
            // opcional: 'replyTo' => ['email' => $fromEmail, 'name' => $fromName],
        ];

        $resp = Http::withHeaders([
            'api-key'      => $key,
            'accept'       => 'application/json',
            'content-type' => 'application/json',
        ])
            ->timeout(20)
            ->post('https://api.brevo.com/v3/smtp/email', $payload);

        if ($resp->successful()) {
            return true;
        }

        // Manejo de errores informativo
        $status = $resp->status();
        $body   = $resp->json() ?? $resp->body();
        $msg    = "[BrevoApi] HTTP {$status} " . (is_string($body) ? $body : json_encode($body));
        Log::error($msg);

        // Devuelve string para guardar en `campaign_recipients.error`
        return "Brevo error {$status}";
    }
}
