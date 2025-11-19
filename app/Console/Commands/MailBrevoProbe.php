<?php

namespace App\Console\Commands;

use App\Mail\CampaignMailable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailBrevoProbe extends Command
{
    protected $signature = 'mail:brevo-probe {to}';
    protected $description = 'Envía un correo simple para probar Brevo SMTP';

    public function handle()
    {
        $to = $this->argument('to');

        $subject = 'Prueba SMTP Brevo '.now();
        $html = '<p>Hola, esto es una PRUEBA SMTP desde '.config('app.name').'.</p>';

        $this->info("Enviando a {$to}...");
        try {
            Mail::to($to)->send(new CampaignMailable($subject, $html));
            $this->info('Envío solicitado. Revisa tu inbox/spam/promotions.');
        } catch (\Throwable $e) {
            Log::error('[mail:brevo-probe] '.$e->getMessage());
            $this->error($e->getMessage());
            return self::FAILURE;
        }
        return self::SUCCESS;
    }
}