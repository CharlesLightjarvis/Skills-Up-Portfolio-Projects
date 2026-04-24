<?php

namespace App\Listeners;

use App\Events\TodoCreated;
use App\Notifications\SendTelegramMessageNotification;
use Illuminate\Contracts\Queue\ShouldQueueAfterCommit;

class LogTodo implements ShouldQueueAfterCommit
{
    public function handle(TodoCreated $event): void
    {
        logger()->info('Todo created', [
            'title' => $event->todo->title,
            'user_id' => $event->user->id,
        ]);

        try {
            $event->user->notify(new SendTelegramMessageNotification($event->todo));

            logger()->info('Notification sent successfully');
        } catch (\Throwable $e) {
            logger()->error('Telegram notification failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}