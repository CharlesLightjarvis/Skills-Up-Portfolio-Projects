<?php

namespace App\Console\Commands;

use App\Ai\Agents\PersonalAssistant;
use Illuminate\Console\Command;
use Laravel\Ai\Streaming\Events\TextDelta;

use function Laravel\Prompts\info;
use function Laravel\Prompts\stream;
use function Laravel\Prompts\task;
use function Laravel\Prompts\text;
use function Laravel\Prompts\title;

class AssistantChat extends Command
{
    protected $signature = 'chat';

    protected $description = 'Chat with your personal AI assistant';

    public function handle(): void
    {
        title('🤖 Personal Assistant');

        info('Welcome! I\'m your personal AI assistant. Type "exit" to quit.');
        $this->newLine();

        $user = (object) ['id' => 'cli-user'];
        $assistant = PersonalAssistant::make()->continueLastConversation($user);

        while (true) {
            $input = text(
                label: 'You',
                placeholder: 'Ask me anything...',
                required: true,
            );

            if (strtolower(trim($input)) === 'exit') {
                info('Goodbye! 👋');
                break;
            }

            $this->newLine();

            $events = task('Thinking...', function () use ($assistant, $input) {
                $collected = [];

                $assistant->stream($input)->each(function ($event) use (&$collected) {
                    $collected[] = $event;
                });

                return $collected;
            });

            $output = stream();

            foreach ($events as $event) {
                if ($event instanceof TextDelta) {
                    $output->append($event->delta);
                }
            }

            $output->close();

            $this->newLine();
        }
    }
}
