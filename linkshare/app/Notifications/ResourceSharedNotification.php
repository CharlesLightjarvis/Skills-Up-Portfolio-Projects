<?php

namespace App\Notifications;

use App\Models\Collection;
use App\Models\Share;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ResourceSharedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Share $share)
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
        return ['database'];
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        $shareable = $this->share->shareable;

        $isCollection = $this->share->shareable_type === Collection::class;

        $url = $isCollection
            ? route('shared.collections.show', $this->share->id)
            : null;

        return [
            'share_id' => $this->share->id,
            'owner_id' => $this->share->owner_id,
            'owner_name' => $this->share->owner->name,
            'shareable_id' => $this->share->shareable_id,
            'shareable_type' => $isCollection ? 'collection' : 'link',
            'shareable_name' => $isCollection
                ? $shareable->name
                : $shareable->title,
            'url' => $url,
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
