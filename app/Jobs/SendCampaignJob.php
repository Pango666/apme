<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\CampaignRecipient;
use App\Models\NewsletterSubscriber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 1200; // 20 min

    public function __construct(public int $campaignId) {}

    public function handle(): void
    {
        $campaign = Campaign::findOrFail($this->campaignId);

        // Cola de envío (throttle simple)
        $chunk = 100;            // envíos por tanda
        $sleepMs = 1200;         // ~50–60/min para SMTP free
        $query = CampaignRecipient::where('campaign_id', $campaign->id)->where('status', 'queued');

        $query->chunkById($chunk, function ($batch) use ($campaign, $sleepMs) {
            foreach ($batch as $r) {
                // saltar si el suscriptor ya se dio de baja
                $sub = NewsletterSubscriber::find($r->subscriber_id);
                if (!$sub || $sub->status !== 'subscribed') {
                    $r->update(['status' => 'failed', 'error' => 'not subscribed']);
                    $campaign->increment('error_count');
                    continue;
                }

                try {
                    // Personalización mínima
                    $html = $campaign->html;
                    $unsubscribe = route('newsletter.unsubscribe', $sub->token);
                    $html = str_replace('{{unsubscribe_url}}', $unsubscribe, $html);

                    Mail::send([], [], function ($m) use ($campaign, $r, $html) {
                        $m->to($r->email)
                            ->subject($campaign->subject)
                            ->html($html);
                        if ($campaign->preheader) {
                            $m->withSwiftMessage(function ($message) use ($campaign) {
                                $headers = $message->getHeaders();
                                $headers->addTextHeader('X-Preheader', $campaign->preheader);
                            });
                        }
                    });

                    $r->update(['status' => 'sent', 'sent_at' => now(), 'error' => null]);
                    $campaign->increment('sent_count');
                } catch (\Throwable $e) {
                    $r->update(['status' => 'failed', 'error' => substr($e->getMessage(), 0, 500)]);
                    $campaign->increment('error_count');
                }

                usleep($sleepMs * 1000);
            }
        });

        // Cerrar campaña
        if (!CampaignRecipient::where('campaign_id', $campaign->id)->where('status', 'queued')->exists()) {
            $campaign->update(['status' => 'sent']);
        }
    }
}
