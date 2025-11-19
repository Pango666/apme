<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class SubscriberController extends Controller
{
    public function index(Request $r)
    {
        $q = NewsletterSubscriber::query();
        if ($r->filled('status')) $q->where('status', $r->status);
        if ($r->filled('s')) $q->where('email', 'like', '%' . $r->s . '%');
        $items = $q->latest()->paginate(20)->withQueryString();
        return view('admin.newsletter.subscribers.index', compact('items'));
    }

    public function destroy(NewsletterSubscriber $subscriber)
    {
        $subscriber->delete();
        return back()->with('ok', 'Suscriptor eliminado');
    }
}
