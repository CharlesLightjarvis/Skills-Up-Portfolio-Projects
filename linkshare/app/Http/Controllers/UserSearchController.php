<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserSearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate(['q' => 'nullable|string|max:100']);

        $query = trim($request->q ?? '');

        $users = $query === '' ? [] : User::where('id', '!=', auth()->id())
            ->where(function ($builder) use ($query) {
                $builder->where('email', $query)
                    ->orWhere('name', 'LIKE', "%{$query}%");
            })
            ->select('id', 'name', 'email')
            ->limit(8)
            ->get();

        return Inertia::render('shares/search', [
            'users' => $users,
            'query' => $query,
        ]);
    }
}
