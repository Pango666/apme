<?php

namespace App\Http\Controllers;

use App\Mail\NewsletterConfirmMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $r)
    {
        $data = $r->validate(['email' => 'required|email', 'name' => 'nullable|string|max:150']);
        $sub = NewsletterSubscriber::firstOrCreate(
            ['email' => strtolower($data['email'])],
            ['name' => $data['name'] ?? null, 'status' => 'pending']
        );
        if ($sub->status !== 'subscribed') {
            Mail::to($sub->email)->queue(new NewsletterConfirmMail($sub));
        }
        return back()->with('ok', 'Revisa tu buzón para confirmar la suscripción.');
    }

    public function confirm(string $token)
    {
        $sub = NewsletterSubscriber::where('token', $token)->firstOrFail();
        $sub->update(['status' => 'subscribed', 'confirmed_at' => now()]);
        return view('newsletter.confirmed', compact('sub'));
    }

    public function unsubscribe(string $token)
    {
        $sub = NewsletterSubscriber::where('token', $token)->firstOrFail();
        $sub->update(['status' => 'unsubscribed']);
        return view('newsletter.unsubscribed', compact('sub'));
    }
}
