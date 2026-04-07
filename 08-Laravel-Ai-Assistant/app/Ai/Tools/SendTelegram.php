<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\Http;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class SendTelegram implements Tool
{
    public function description(): Stringable|string
    {
        return 'Send a message to the user via Telegram.';
    }

    public function handle(Request $request): Stringable|string
    {
        Http::post('https://api.telegram.org/bot' . config('services.telegram.bot_token') . '/sendMessage', [
            'chat_id' => config('services.telegram.chat_id'),
            'text' => $request['message'],
            'parse_mode' => 'Markdown',
        ]);

        return 'Message sent to Telegram.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'message' => $schema->string()->required(),
        ];
    }
}
