<?php

namespace App\Http\Controllers;

use App\Actions\Collections\ImportSharedCollectionAction;
use App\Models\Share;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SharedCollectionController extends Controller
{
    public function show(Request $request, Share $share): Response
    {
        abort_if($share->recipient_id !== $request->user()->id, 403);
        abort_unless($share->shareable_type === \App\Models\Collection::class, 404);

        $collection = $share->shareable->load('links');

        return Inertia::render('shared/collection', [
            'share' => $share,
            'collection' => $collection,
            'owner' => $share->owner->only('id', 'name'),
        ]);
    }

    public function import(Request $request, Share $share, ImportSharedCollectionAction $action): RedirectResponse
    {
        abort_if($share->recipient_id !== $request->user()->id, 403);
        abort_unless($share->shareable_type === \App\Models\Collection::class, 404);

        $collection = $action->handle($request->user(), $share);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Collection importée.']);

        return redirect()->route('collections.show', $collection);
    }
}
