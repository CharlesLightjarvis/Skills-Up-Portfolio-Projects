<?php

namespace App\Http\Controllers;

use App\Ai\Agents\PersonalAssistant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        $message = $request->input('message');

        if (! $message || ! isset($message['text'])) {
            return response()->json(['ok' => true]);
        }

        $chatId = $message['chat']['id'];
        $text = $message['text'];

        // Only respond to the configured chat
        if ((string) $chatId !== config('services.telegram.chat_id')) {
            return response()->json(['ok' => true]);
        }

        // Handle /reset command
        if (strtolower(trim($text)) === '/reset') {
            DB::table('agent_conversations')->where('user_id', $chatId)->delete();
            $this->sendMessage($chatId, '🔄 Conversation reset. Fresh start!');

            return response()->json(['ok' => true]);
        }

        // Show typing indicator
        $this->sendTyping($chatId);

        try {
            // Run the agent
            $user = (object) ['id' => $chatId];
            $response = PersonalAssistant::make()->continueLastConversation($user)->prompt($text);

            // Send the response
            $this->sendMessage($chatId, (string) $response);
        } catch (Throwable $e) {
            Log::error('Agent error: ' . $e->getMessage(), ['exception' => $e]);

            $error = match (true) {
                str_contains($e->getMessage(), 'overloaded') => '⚠️ AI provider is overloaded. Please try again in a moment.',
                str_contains($e->getMessage(), 'rate') => '⚠️ Rate limit hit. Please wait a moment and try again.',
                default => '⚠️ Something went wrong: ' . class_basename($e) . ' — ' . $e->getMessage(),
            };

            $this->sendMessage($chatId, $error);
        }

        return response()->json(['ok' => true]);
    }

    private function sendTyping(string $chatId): void
    {
        Http::post($this->apiUrl('sendChatAction'), [
            'chat_id' => $chatId,
            'action' => 'typing',
        ]);
    }

    private function sendMessage(string $chatId, string $text): void
    {
        // Telegram messages have a 4096 character limit
        foreach (str_split($text, 4096) as $chunk) {
            Http::post($this->apiUrl('sendMessage'), [
                'chat_id' => $chatId,
                'text' => $chunk,
                'parse_mode' => 'Markdown',
            ]);
        }
    }

    private function apiUrl(string $method): string
    {
        return 'https://api.telegram.org/bot' . config('services.telegram.bot_token') . '/' . $method;
    }
}
