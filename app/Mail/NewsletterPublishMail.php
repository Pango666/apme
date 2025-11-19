<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterPublishMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $subjectLine,
        public string $title,
        public string $excerpt,
        public string $coverUrl,
        public string $ctaUrl,
        public string $unsubscribeUrl
    ) {}

    public function build()
    {
        return $this->subject($this->subjectLine)
            ->view('mail.newsletter-publish');
    }
}
