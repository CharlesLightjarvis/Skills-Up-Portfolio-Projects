<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Link;
use App\Models\Share;
use App\Notifications\ResourceSharedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShareController extends Controller
{
    private array $typeMap = [
        'collection' => Collection::class,
        'link' => Link::class,
    ];

    public function store(Request $request)
    {
        $data = $request->validate([
            'recipient_id' => ['required', 'integer', 'exists:users,id', 'not_in:'.auth()->id()],
            'shareable_type' => ['required', 'in:collection,link'],
            'shareable_id' => ['required', 'integer'],
        ]);

        $modelClass = $this->typeMap[$data['shareable_type']];

        // Vérifie que la ressource appartient bien à l'utilisateur connecté
        if ($data['shareable_type'] === 'collection') {
            $shareable = Collection::where('user_id', auth()->id())
                ->findOrFail($data['shareable_id']);
        } else {
            // Link n'a pas de user_id direct, on passe par la collection
            $shareable = Link::whereHas('collection', function ($q) {
                $q->where('user_id', auth()->id());
            })->findOrFail($data['shareable_id']);
        }

        // Doublon ?
        $alreadyShared = Share::where([
            'owner_id' => auth()->id(),
            'recipient_id' => $data['recipient_id'],
            'shareable_type' => $modelClass,
            'shareable_id' => $shareable->id,
        ])->exists();

        if ($alreadyShared) {
            return back()->with('error', 'Déjà partagé avec cet utilisateur.');
        }

        DB::transaction(function () use ($data, $modelClass, $shareable) {
            $share = Share::create([
                'owner_id' => auth()->id(),
                'recipient_id' => $data['recipient_id'],
                'shareable_type' => $modelClass,
                'shareable_id' => $shareable->id,
                'permission' => 'view',
            ]);

            $share->load(['owner', 'shareable']);

            // Notification synchrone, stockée en base, sans worker
            $share->recipient->notify(new ResourceSharedNotification($share));
        });

        return back()->with('success', 'Partagé avec succès !');
    }

    public function destroy(Share $share)
    {
        abort_unless($share->owner_id === auth()->id(), 403);

        $share->delete();

        return back()->with('success', 'Partage supprimé.');
    }
}
