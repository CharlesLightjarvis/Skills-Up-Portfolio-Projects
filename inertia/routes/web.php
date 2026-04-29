<?php

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::inertia('/', 'welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    /* le premier dashboard cest l'url et le deuxieme cest le composant dans resources/views/js/pages/dashboard.tsx
     */
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');

    Route::get('products', function (Request $request) {
        $searchQuery = $request->query('search');

        return Inertia::render('products/index', [


            'products' => Product::query()
                        ->when($searchQuery, function ($query, $search) {
                            $query->where('name', 'like', "%{$search}%");
                        })
                        ->paginate(10)
                        ->withQueryString()
                        ->through(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'description' => $product->description,
                    'price' => $product->price,
                    'image' => $product->image,
                ];
                
            }),
             
               'filters' => [
                'search' => $searchQuery,
            ],
        ]);
    })->name('products.index');

    Route::get('products/create', function () {
        return Inertia::render('products/create');
    })->name('products.create');

    Route::get('products/{product}/edit', function (Product $product) {
        return Inertia::render('products/edit', [
            'product' => $product->only(['id', 'name', 'description', 'price', 'image', 'status']),
        ]);
    })->name('products.edit');

    Route::put('products/{product}', function (Request $request, Product $product) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ]);
        

        $product->update($validated);

        return to_route('products.index');
    })->name('products.update');

    Route::delete('products/{product}', function (Product $product) {
        $product->delete();

        return to_route('products.index');
    })->name('products.destroy');

    Route::post('products', function (Request $request) {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'in:active,inactive'],
        ]);

        $request->user()->products()->create($validated);

        return to_route('products.index');
    })->name('products.store');

});

require __DIR__.'/settings.php';
