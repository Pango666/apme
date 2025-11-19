<?php

namespace App\Jobs;

use App\Mail\NewsletterPublishMail;
use App\Models\NewsletterSubscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class BroadcastNewsletter implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public function __construct(
        public string $subjectLine,
        public string $title,
        public string $excerpt,
        public string $coverUrl,
        public string $ctaUrl
    ) {
        $this->onQueue('mail'); // cola dedicada
    }

    public function handle(): void
    {
        NewsletterSubscriber::subscribed()
            ->select(['email', 'name', 'unsub_token'])
            ->chunk(200, function ($chunk) {
                foreach ($chunk as $sub) {
                    $unsubscribe = route('newsletter.unsubscribe', $sub->unsub_token);
                    Mail::to($sub->email)->queue(
                        new NewsletterPublishMail(
                            $this->subjectLine,
                            $this->title,
                            $this->excerpt,
                            $this->coverUrl,
                            $this->ctaUrl,
                            $unsubscribe
                        )
                    );
                }
            });
    }
}
