<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index()
    {
        $subs = NewsletterSubscriber::query()
            ->orderByRaw("FIELD(status,'subscribed','pending','unsubscribed'), email")
            ->paginate(20);

        return view('admin.newsletter.index', compact('subs'));
    }
}
