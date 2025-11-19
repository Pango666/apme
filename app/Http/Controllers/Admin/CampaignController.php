<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendCampaignJob;
use App\Models\Campaign;
use App\Models\CampaignRecipient;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = Campaign::query()->latest()->paginate(15);
        $subCount  = NewsletterSubscriber::where('status', 'subscribed')->count();

        return view('admin.campaigns.index', compact('campaigns', 'subCount'));
    }

    public function create()
    {
        $campaign = new Campaign(['status' => 'draft']);
        return view('admin.newsletter.campaigns.form', compact('campaign'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'name'      => ['required', 'max:190'],
            'subject'   => ['required', 'max:190'],
            'preheader' => ['nullable', 'max:190'],
            'html'      => ['required', 'string'],
        ]);

        $data['scheduled_at'] = now();
        $data['status']       = 'draft';

        $c = Campaign::create($data);

        // Sincroniza recipients de una (solo suscritos)
        $this->syncRecipients($c->id);

        return back()->with('ok', 'Campaña creada y destinatarios sincronizados.');
    }

    public function edit(Campaign $campaign)
    {
        return view('admin.newsletter.campaigns.form', compact('campaign'));
    }

    public function update(Request $r, Campaign $campaign)
    {
        $data = $r->validate([
            'name' => 'required|max:180',
            'subject' => 'required|max:190',
            'preheader' => 'nullable|max:190',
            'html' => 'required|string',
            'scheduled_at' => 'nullable|date'
        ]);
        if ($campaign->status === 'sent') return back()->with('err', 'La campaña ya fue enviada');
        $data['status'] = $data['scheduled_at'] ? 'scheduled' : 'draft';
        $campaign->update($data);
        return back()->with('ok', 'Guardado');
    }

    public function destroy(Campaign $campaign)
    {
        if ($campaign->status === 'sending' || $campaign->status === 'sent') abort(403);
        $campaign->delete();
        return back()->with('ok', 'Eliminada');
    }

    public function sendNow(Campaign $campaign)
    {
        abort_if($campaign->status === 'sent', 403);
        // Generar recipients (solo suscritos confirmados)
        DB::transaction(function () use ($campaign) {
            $subs = NewsletterSubscriber::where('status', 'subscribed')->pluck('id', 'email');
            foreach ($subs as $email => $sid) {
                CampaignRecipient::firstOrCreate([
                    'campaign_id' => $campaign->id,
                    'subscriber_id' => $sid,
                ], ['email' => $email]);
            }
            $campaign->update(['status' => 'sending', 'scheduled_at' => now()]);
        });

        dispatch(new SendCampaignJob($campaign->id));
        return back()->with('ok', 'Envío iniciado en segundo plano.');
    }

    public function send(Campaign $campaign)
    {
        // Si no tiene recipients, sincroniza
        if (!CampaignRecipient::where('campaign_id', $campaign->id)->exists()) {
            $this->syncRecipients($campaign->id);
        }

        $campaign->update(['status' => 'sending', 'scheduled_at' => now()]);
        dispatch(new SendCampaignJob($campaign->id));

        return back()->with('ok', 'Envío programado. Revisa el log y la tabla de recipients.');
    }

    public function sync(Campaign $campaign)
    {
        $count = $this->syncRecipients($campaign->id);
        return back()->with('ok', "Destinatarios sincronizados: {$count}");
    }

    private function syncRecipients(int $campaignId): int
    {
        $subs = NewsletterSubscriber::where('status', 'subscribed')->pluck('id', 'email');

        $inserted = 0;
        foreach ($subs as $email => $sid) {
            $created = CampaignRecipient::firstOrCreate(
                ['campaign_id' => $campaignId, 'subscriber_id' => $sid],
                ['email' => $email, 'status' => 'queued']
            );
            if ($created->wasRecentlyCreated) $inserted++;
        }
        return $inserted;
    }
}
