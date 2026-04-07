<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class SetTelegramWebhook extends Command
{
    protected $signature = 'telegram:set-webhook {url : The public URL of your application}';

    protected $description = 'Register the Telegram bot webhook';

    public function handle(): void
    {
        $url = $this->argument('url') . '/telegram/webhook';

        $response = Http::post(
            'https://api.telegram.org/bot' . config('services.telegram.bot_token') . '/setWebhook',
            ['url' => $url]
        );

        if ($response->json('ok')) {
            $this->info("✅ Webhook set to: {$url}");
        } else {
            $this->error('Failed: ' . $response->json('description'));
        }
    }
}
