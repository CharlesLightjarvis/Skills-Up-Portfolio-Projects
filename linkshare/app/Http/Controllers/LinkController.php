<?php

namespace App\Http\Controllers;

use App\Actions\Links\CreateLinkAction;
use App\Actions\Links\DeleteLinkAction;
use App\Actions\Links\FetchLinkPreviewAction;
use App\Actions\Links\UpdateLinkAction;
use App\Http\Requests\Links\StoreLinkRequest;
use App\Http\Requests\Links\UpdateLinkRequest;
use App\Models\Collection;
use App\Models\Link;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use RuntimeException;

class LinkController extends Controller
{
    public function __construct(
        private readonly CreateLinkAction $createAction,
        private readonly UpdateLinkAction $updateAction,
        private readonly DeleteLinkAction $deleteAction,
        private readonly FetchLinkPreviewAction $fetchPreviewAction,
    ) {}

    public function create(Collection $collection): Response
    {
        abort_if($collection->user_id !== auth()->id(), 403);

        return Inertia::render('collections/links/create', [
            'collection' => $collection,
        ]);
    }

    public function edit(Collection $collection, Link $link): Response
    {
        abort_if($collection->user_id !== auth()->id(), 403);

        return Inertia::render('collections/links/edit', [
            'collection' => $collection,
            'link' => $link,
        ]);
    }

    public function store(StoreLinkRequest $request, Collection $collection): RedirectResponse
    {
        abort_if($collection->user_id !== auth()->id(), 403);

        $links = $request->validated()['links'];
        foreach ($links as $linkData) {
            $this->createAction->handle($collection, $linkData);
        }

        $count = count($links);
        Inertia::flash('toast', [
            'type' => 'success',
            'message' => $count === 1 ? 'Lien ajouté.' : "{$count} liens ajoutés.",
        ]);

        return back();
    }

    public function update(UpdateLinkRequest $request, Collection $collection, Link $link): RedirectResponse
    {
        abort_if($collection->user_id !== auth()->id(), 403);

        $this->updateAction->handle($link, $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Lien modifié.']);

        return back();
    }

    public function destroy(Collection $collection, Link $link): RedirectResponse
    {
        abort_if($collection->user_id !== auth()->id(), 403);

        $this->deleteAction->handle($link);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Lien supprimé.']);

        return back();
    }

    public function preview(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'url' => ['required', 'url', 'max:2048'],
        ]);

        try {
            $preview = $this->fetchPreviewAction->handle($validated['url']);

            return response()->json($preview);
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}
