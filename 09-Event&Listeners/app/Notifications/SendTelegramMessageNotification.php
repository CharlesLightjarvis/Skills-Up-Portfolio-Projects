<?php

namespace App\Notifications;

use CharlesLightjarvis\Todo\Models\Todo;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class SendTelegramMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected const URL = "https://dallico.com";

    /**
     * Create a new notification instance.
     */
    public function __construct(public Todo $todo)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['telegram'];
    }

    /**
     * Get the mail representation of the notification.
     */
      public function toTelegram($notifiable)
    {
        $url = self::URL;

        return TelegramMessage::create()
            // Markdown supported.
            ->content("Hello there!")
            ->line("A need has been created and assigned to you: {$this->todo->title}")
            ->line("Due date: " . ($this->todo->due_at?->format('F j, Y, g:i a') ?? 'N/A'))
            ->line("Priority: {$this->todo->priority->value}")
            ->line("Thank you!")

            // (Optional) Blade template for the content.
            // ->view('notification', ['url' => $url])

            // (Optional) Inline Buttons
            ->button('View Todo', $url)
            ->button('Download Todo', $url);
            // (Optional) Inline Button with callback. You can handle callback in your bot instance
            // ->buttonWithCallback('Confirm', 'confirm_todo ' . $this->todo->id);
    }
}
