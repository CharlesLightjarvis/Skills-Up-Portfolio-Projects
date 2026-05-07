<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class NotificationController extends Controller
{
    public function markRead(string $id) : RedirectResponse
    {
        auth()->user()
            ->notifications()
            ->where('id', $id)
            ->first()
            ?->markAsRead();

        return back();
    }

    public function markAllRead() : RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back();
    }
}
