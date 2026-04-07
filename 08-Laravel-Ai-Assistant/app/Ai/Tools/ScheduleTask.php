<?php

namespace App\Ai\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Support\Facades\File;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

class ScheduleTask implements Tool
{
    public function description(): Stringable|string
    {
        return 'Schedule a recurring task. The task will prompt the AI assistant with the given message at the specified time every day.';
    }

    public function handle(Request $request): Stringable|string
    {
        $tasksFile = storage_path('app/scheduled-tasks.json');
        $tasks = File::exists($tasksFile) ? json_decode(File::get($tasksFile), true) : [];

        $tasks[] = [
            'time' => $request['time'],
            'prompt' => $request['prompt'],
            'created_at' => now()->toDateTimeString(),
        ];

        File::put($tasksFile, json_encode($tasks, JSON_PRETTY_PRINT));

        return "Scheduled daily task at {$request['time']}: {$request['prompt']}";
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'time' => $schema->string()->required(),
            'prompt' => $schema->string()->required(),
        ];
    }
}
