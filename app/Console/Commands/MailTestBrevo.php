<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MailTestBrevo extends Command
{
    protected $signature = 'mail:test-brevo {to?}';
    protected $description = 'Envía un correo de prueba usando Brevo SMTP';

    public function handle()
    {
        $to = $this->argument('to') ?? config('mail.from.address');

        try {
            Mail::raw('Prueba Brevo SMTP OK: ' . now(), function ($m) use ($to) {
                $m->to($to)->subject('Brevo SMTP funcionando');
            });

            $this->info("Enviado a: {$to}");
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('ERROR: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
