<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $request->user(),
            ],
            'notifications' => fn () => $request->user() ? [
                'unread_count' => $request->user()->unreadNotifications()->count(),
                'items' => $request->user()
                    ->notifications()
                    ->latest()
                    ->take(10)
                    ->get()
                    ->map(fn ($n) => [
                        'id' => $n->id,
                        'data' => $n->data,
                        'read' => ! is_null($n->read_at),
                        'created_at' => $n->created_at->diffForHumans(),
                    ]),
            ] : null,
            // Résultats de recherche d'utilisateur pour le modal de partage
            'userSearchResults' => fn () => $request->user() && $request->has('q')
                ? User::where('id', '!=', $request->user()->id)
                    ->where(function ($builder) use ($request) {
                        $q = trim($request->q);
                        $builder->where('email', $q)
                            ->orWhere('name', 'LIKE', "%{$q}%");
                    })
                    ->select('id', 'name', 'email')
                    ->limit(8)
                    ->get()
                : [],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
