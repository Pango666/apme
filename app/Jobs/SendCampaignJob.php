<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\CampaignRecipient;
use App\Services\Brevo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SendCampaignJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public int $campaignId;

    /** reintentos y backoff del job */
    public int $tries   = 3;
    public int $backoff = 30; // segundos

    /**
     * @param  int  $campaignId
     */
    public function __construct(int $campaignId)
    {
        $this->onQueue('mail');
        $this->campaignId = $campaignId;
    }

    public function handle(): void
    {
        $campaign = Campaign::find($this->campaignId);
        if (!$campaign) {
            Log::warning("[SendCampaignJob] Campaña no encontrada id={$this->campaignId}");
            return;
        }

        $queued = CampaignRecipient::where('campaign_id', $campaign->id)
            ->where('status', 'queued')
            ->count();

        Log::info("[SendCampaignJob] Iniciando envío campaña={$campaign->id} queued={$queued}");

        if ($queued === 0) {
            $this->consolidateCampaign($campaign);
            return;
        }

        $plusSent = 0;
        $plusErr  = 0;

        CampaignRecipient::where('campaign_id', $campaign->id)
            ->where('status', 'queued')
            ->orderBy('id')
            ->chunkById(100, function ($chunk) use ($campaign, &$plusSent, &$plusErr) {
                foreach ($chunk as $r) {
                    Log::info("[SendCampaignJob] Enviando a {$r->email} (recipient_id={$r->id})");

                    try {
                        $ok = Brevo::send(
                            to: $r->email,
                            subject: $campaign->subject,
                            html: $campaign->html,
                            fromEmail: config('mail.from.address', 'noticias@apme.bo'),
                            fromName: config('mail.from.name', 'APME'),
                            text: strip_tags($campaign->html),
                            tags: ['newsletter', 'campaign:' . $campaign->id]
                        );

                        if ($ok === true) {
                            $r->update(['status' => 'sent', 'sent_at' => now(), 'error' => null]);
                            $plusSent++;
                        } else {
                            $r->update(['status' => 'failed', 'error' => is_string($ok) ? mb_substr($ok, 0, 500) : 'Brevo API send failed']);
                            $plusErr++;
                        }

                        usleep(120 * 1000); // 120ms anti rate limit
                    } catch (Throwable $e) {
                        $r->update(['status' => 'failed', 'error' => mb_substr($e->getMessage(), 0, 500)]);
                        $plusErr++;
                        if ($this->isRateLimitOrTimeout($e)) {
                            usleep(800 * 1000);
                        }
                        Log::error("[SendCampaignJob] Error enviando a {$r->email}: " . $e->getMessage());
                    }
                }
            });

        $this->consolidateCampaign($campaign);

        Log::info("[SendCampaignJob] Final campaña={$campaign->id} sent+={$plusSent} errors+={$plusErr} " .
            "total_sent={$campaign->sent_count} total_err={$campaign->error_count} status={$campaign->status}");
    }

    private function consolidateCampaign(Campaign $campaign): void
    {
        $total  = CampaignRecipient::where('campaign_id', $campaign->id)->count();
        $sent   = CampaignRecipient::where('campaign_id', $campaign->id)->where('status', 'sent')->count();
        $failed = CampaignRecipient::where('campaign_id', $campaign->id)->where('status', 'failed')->count();
        $left   = CampaignRecipient::where('campaign_id', $campaign->id)->where('status', 'queued')->count();

        $campaign->sent_count  = $sent;
        $campaign->error_count = $failed;

        $campaign->status = $left > 0
            ? 'sending'
            : ($sent === 0 && $failed === 0 ? 'empty'
                : ($sent === $total ? 'sent'
                    : ($sent > 0 && $failed > 0 ? 'partial' : 'failed')));

        $campaign->save();

        Log::info("[SendCampaignJob] Consolidado campaña={$campaign->id} queued_left={$left} sent={$sent} failed={$failed} total={$total} status={$campaign->status}");
    }

    private function isRateLimitOrTimeout(Throwable $e): bool
    {
        $m = strtolower($e->getMessage());
        return str_contains($m, '429') || str_contains($m, 'rate limit') || str_contains($m, 'timed out') || str_contains($m, 'timeout');
    }
}
