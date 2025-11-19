<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use App\Services\Brevo;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $r)
    {
        $data = $r->validate([
            'email' => ['required', 'email'],
            'name'  => ['nullable', 'string', 'max:150'],
        ]);

        $sub = NewsletterSubscriber::firstOrCreate(
            ['email' => strtolower($data['email'])],
            ['name'  => $data['name'] ?? null, 'status' => 'pending']
        );

        // Asegura token por si el registro previo no lo tenía
        if (empty($sub->token)) {
            $sub->token = bin2hex(random_bytes(20));
            $sub->save();
        }

        if ($sub->status !== 'subscribed') {
            $confirmUrl = route('newsletter.confirm', $sub->token); // <-- FALTABA
            $html = view('emails.newsletter.confirm', [
                'sub'        => $sub,
                'confirmUrl' => $confirmUrl,
            ])->render();

            Brevo::send(
                to: $sub->email,
                subject: 'Confirma tu suscripción a APME',
                html: $html,
                fromEmail: config('mail.from.address', 'noticias@apme.bo'),
                fromName: config('mail.from.name', 'APME'),
                text: "Hola, confirma tu suscripción a APME:\n$confirmUrl\n\nSi no fuiste tú, ignora este mensaje.",
                tags: ['newsletter', 'confirm']
            );
        }

        return back()
            ->withInput()
            ->with('newsletter_ok', '¡Listo! Revisa tu correo para confirmar la suscripción.');
    }

    public function confirm(string $token)
    {
        $sub = NewsletterSubscriber::where('token', $token)->firstOrFail();
        $sub->update(['status' => 'subscribed', 'confirmed_at' => now()]);

        return view('emails.newsletter.confirmed', compact('sub'));
    }

    public function unsubscribe(string $token)
    {
        $sub = NewsletterSubscriber::where('token', $token)->firstOrFail();
        $sub->update(['status' => 'unsubscribed']);

        return view('emails.newsletter.unsubscribed', compact('sub'));
    }
}
