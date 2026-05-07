<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __invoke(): Response
    {
        $user = auth()->user();

        $linkQuery = fn () => Link::whereHas(
            'collection',
            fn ($q) => $q->where('user_id', $user->id),
        );

        $recentLinks = $linkQuery()
            ->with('collection:id,name,color')
            ->latest()
            ->limit(8)
            ->get(['id', 'collection_id', 'url', 'title', 'domain', 'is_favorite', 'created_at']);

        return Inertia::render('dashboard', [
            'stats' => [
                'links' => $linkQuery()->count(),
                'collections' => $user->collections()->count(),
                'favorites' => $linkQuery()->where('is_favorite', true)->count(),
                'this_month' => $linkQuery()
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
            ],
            'recent_links' => $recentLinks,
        ]);
    }
}
