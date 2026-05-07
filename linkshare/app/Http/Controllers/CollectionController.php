<?php

namespace App\Http\Controllers;

use App\Actions\Collections\CreateCollectionAction;
use App\Actions\Collections\DeleteCollectionAction;
use App\Actions\Collections\UpdateCollectionAction;
use App\Http\Requests\Collections\StoreCollectionRequest;
use App\Http\Requests\Collections\UpdateCollectionRequest;
use App\Models\Collection;
use App\Repositories\Collections\CollectionRepository;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class CollectionController extends Controller
{
    public function __construct(
        private readonly CollectionRepository $repository,
        private readonly CreateCollectionAction $createAction,
        private readonly UpdateCollectionAction $updateAction,
        private readonly DeleteCollectionAction $deleteAction,
    ) {}

    public function index(): Response
    {
        return Inertia::render('collections/index', [
            'collections' => $this->repository->paginate(auth()->user(), 8),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('collections/create');
    }

    public function store(StoreCollectionRequest $request): RedirectResponse
    {
        $this->createAction->handle($request->user(), $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Collection créée.']);

        return redirect()->route('collections.index');
    }

    public function show(Collection $collection): Response
    {
        abort_if($collection->user_id !== auth()->id(), 403);

        return Inertia::render('collections/show', [
            'collection' => $collection,
            'links' => $collection->links()->latest()->paginate(20),
        ]);
    }

    public function edit(Collection $collection): Response
    {
        abort_if($collection->user_id !== auth()->id(), 403);

        return Inertia::render('collections/edit', [
            'collection' => $collection,
            'links' => $collection->links()->latest()->get(),
        ]);
    }

    public function update(UpdateCollectionRequest $request, Collection $collection): RedirectResponse
    {
        $this->updateAction->handle($collection, $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Collection modifiée.']);

        return redirect()->route('collections.show', $collection);
    }

    public function destroy(Collection $collection): RedirectResponse
    {
        abort_if($collection->user_id !== auth()->id(), 403);

        $this->deleteAction->handle($collection);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Collection supprimée.']);

        return redirect()->route('collections.index');
    }
}
