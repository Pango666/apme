<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignMailable extends Mailable
{
    use Queueable, SerializesModels;

    public string $subjectLine;
    public string $htmlBody;
    public ?string $preheader;

    public function __construct(string $subjectLine, string $htmlBody, ?string $preheader = null)
    {
        $this->subjectLine = $subjectLine;
        $this->htmlBody    = $htmlBody;
        $this->preheader   = $preheader;
    }

    public function build()
    {
        $m = $this->subject($this->subjectLine)
            ->html($this->htmlBody);

        // Sugerencia: cabecera preheader como "invisible"
        if ($this->preheader) {
            $m->withSwiftMessage(function ($message) {
                // nada crítico; el preheader ya está dentro del HTML si lo pones
            });
        }

        return $m;
    }
}
