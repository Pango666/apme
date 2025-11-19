<?php

namespace App\Jobs;

use App\Models\Campaign;
use App\Models\CampaignRecipient;
use App\Mail\CampaignMailable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SendCampaignJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public int $campaignId;

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

        // Tomamos solo los "queued"
        $total = CampaignRecipient::where('campaign_id', $campaign->id)
            ->where('status', 'queued')
            ->count();

        Log::info("[SendCampaignJob] Iniciando envío campaña={$campaign->id} recipients={$total}");

        if ($total === 0) {
            // Si no hay queued, inferimos estado
            $sent   = CampaignRecipient::where('campaign_id',$campaign->id)->where('status','sent')->count();
            $failed = CampaignRecipient::where('campaign_id',$campaign->id)->where('status','failed')->count();
            $campaign->status      = $sent > 0 && $failed === 0 ? 'sent' : ($sent > 0 ? 'partial' : 'empty');
            $campaign->sent_count  = $sent;
            $campaign->error_count = $failed;
            $campaign->save();
            Log::info("[SendCampaignJob] Final campaña={$campaign->id} status={$campaign->status} sent={$sent} failed={$failed}");
            return;
        }

        $sentCount  = 0;
        $errorCount = 0;

        CampaignRecipient::where('campaign_id', $campaign->id)
            ->where('status', 'queued')
            ->orderBy('id')
            ->chunkById(100, function ($chunk) use ($campaign, &$sentCount, &$errorCount) {
                foreach ($chunk as $r) {
                    /** @var \App\Models\CampaignRecipient $r */
                    Log::info("[SendCampaignJob] Enviando a {$r->email} (recipient_id={$r->id})");

                    try {
                        Mail::to($r->email)->send(new CampaignMailable(
                            $campaign->subject,
                            $campaign->html,
                            $campaign->preheader
                        ));

                        // Si no explotó, lo marcamos como enviado
                        $r->status   = 'sent';
                        $r->sent_at  = now();
                        $r->error    = null;
                        $r->save();

                        $sentCount++;
                    } catch (Throwable $e) {
                        Log::error("[SendCampaignJob] Error enviando a {$r->email}: ".$e->getMessage());
                        $r->status = 'failed';
                        $r->error  = substr($e->getMessage(), 0, 500);
                        $r->save();
                        $errorCount++;
                    }
                }
            });

        // Consolidar estado de la campaña
        $totalRecipients = CampaignRecipient::where('campaign_id', $campaign->id)->count();
        $campaign->sent_count  = CampaignRecipient::where('campaign_id', $campaign->id)->where('status', 'sent')->count();
        $campaign->error_count = CampaignRecipient::where('campaign_id', $campaign->id)->where('status', 'failed')->count();

        if ($campaign->sent_count === $totalRecipients) {
            $campaign->status = 'sent';
        } elseif ($campaign->sent_count > 0) {
            $campaign->status = 'partial';
        } else {
            $campaign->status = 'failed';
        }

        $campaign->save();

        Log::info("[SendCampaignJob] Final campaña={$campaign->id} status={$campaign->status} sent+={$sentCount} errors+={$errorCount} total_sent={$campaign->sent_count} total_err={$campaign->error_count}");
    }
}
