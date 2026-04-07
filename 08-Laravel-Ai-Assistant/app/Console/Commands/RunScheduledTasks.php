<?php

namespace App\Console\Commands;

use App\Ai\Agents\PersonalAssistant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class RunScheduledTasks extends Command
{
    protected $signature = 'assistant:run-tasks';

    protected $description = 'Run any scheduled assistant tasks due now';

    public function handle(): void
    {
        $tasksFile = storage_path('app/scheduled-tasks.json');

        if (! File::exists($tasksFile)) {
            return;
        }

        $tasks = json_decode(File::get($tasksFile), true);
        $currentTime = now()->format('H:i');

        foreach ($tasks as $task) {
            if ($task['time'] === $currentTime) {
                $this->info("🤖 Running scheduled task...");

                $response = PersonalAssistant::make()->prompt(
                    $task['prompt'] . ' Then send the result to me via Telegram.'
                );

                $this->line((string) $response);
            }
        }
    }
}
