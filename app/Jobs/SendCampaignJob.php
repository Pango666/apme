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

        // Solo pendientes
        $queuedCount = CampaignRecipient::where('campaign_id', $campaign->id)
            ->where('status', 'queued')
            ->count();

        Log::info("[SendCampaignJob] Iniciando envío campaña={$campaign->id} queued={$queuedCount}");

        if ($queuedCount === 0) {
            $this->consolidateCampaign($campaign);
            return;
        }

        $sentCount  = 0;
        $errorCount = 0;

        // Envía en chunks para evitar cargar todo en memoria
        CampaignRecipient::where('campaign_id', $campaign->id)
            ->where('status', 'queued')
            ->orderBy('id')
            ->chunkById(100, function ($chunk) use ($campaign, &$sentCount, &$errorCount) {
                foreach ($chunk as $r) {
                    /** @var \App\Models\CampaignRecipient $r */
                    Log::info("[SendCampaignJob] Enviando a {$r->email} (recipient_id={$r->id})");

                    try {
                        // ENVIAR por API (sin SMTP)
                        $ok = Brevo::send(
                            to: $r->email,
                            subject: $campaign->subject,
                            html: $campaign->html,
                            fromEmail: config('mail.from.address'),
                            fromName: config('mail.from.name')
                        );

                        if ($ok === true) {
                            $r->update([
                                'status'  => 'sent',
                                'sent_at' => now(),
                                'error'   => null,
                            ]);
                            $sentCount++;
                        } else {
                            // $ok puede traer string con causa
                            $r->update([
                                'status' => 'failed',
                                'error'  => is_string($ok) ? mb_substr($ok, 0, 500) : 'Brevo API send failed',
                            ]);
                            $errorCount++;
                        }

                        // respirito mínimo entre envíos para no trigggear rate limit agresivo
                        usleep(120 * 1000); // 120ms
                    } catch (Throwable $e) {
                        Log::error("[SendCampaignJob] Error enviando a {$r->email}: " . $e->getMessage());
                        $r->update([
                            'status' => 'failed',
                            'error'  => mb_substr($e->getMessage(), 0, 500),
                        ]);
                        $errorCount++;

                        // Si es rate-limit (429) o timeout, damos un respiro corto
                        if ($this->isRateLimitOrTimeout($e)) {
                            usleep(800 * 1000); // 800ms
                        }
                    }
                }
            });

        // Actualiza totales y estado final
        $this->consolidateCampaign($campaign);

        Log::info("[SendCampaignJob] Final campaña={$campaign->id} sent+={$sentCount} errors+={$errorCount} " .
            "total_sent={$campaign->sent_count} total_err={$campaign->error_count} status={$campaign->status}");
    }

    private function consolidateCampaign(Campaign $campaign): void
    {
        $totalRecipients = CampaignRecipient::where('campaign_id', $campaign->id)->count();
        $sent            = CampaignRecipient::where('campaign_id', $campaign->id)->where('status', 'sent')->count();
        $failed          = CampaignRecipient::where('campaign_id', $campaign->id)->where('status', 'failed')->count();
        $queuedLeft      = CampaignRecipient::where('campaign_id', $campaign->id)->where('status', 'queued')->count();

        $campaign->sent_count  = $sent;
        $campaign->error_count = $failed;

        if ($queuedLeft > 0) {
            $campaign->status = 'sending';
        } elseif ($sent === 0 && $failed === 0) {
            $campaign->status = 'empty';
        } elseif ($sent === $totalRecipients) {
            $campaign->status = 'sent';
        } elseif ($sent > 0 && $failed > 0) {
            $campaign->status = 'partial';
        } else {
            $campaign->status = 'failed';
        }

        $campaign->save();

        Log::info("[SendCampaignJob] Consolidado campaña={$campaign->id} " .
            "queued_left={$queuedLeft} sent={$sent} failed={$failed} total={$totalRecipients} status={$campaign->status}");
    }

    private function isRateLimitOrTimeout(Throwable $e): bool
    {
        $msg = strtolower($e->getMessage());
        return str_contains($msg, '429') ||
            str_contains($msg, 'rate limit') ||
            str_contains($msg, 'timed out') ||
            str_contains($msg, 'timeout');
    }
}
