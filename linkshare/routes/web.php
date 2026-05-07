<?php

use App\Http\Controllers\CollectionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\SharedCollectionController;
use App\Http\Controllers\UserSearchController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::inertia('/', 'home/index', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::resource('collections', CollectionController::class);
    Route::resource('collections.links', LinkController::class)
        ->scoped()
        ->only(['create', 'edit', 'store', 'update', 'destroy']);

    Route::post('links/preview', [LinkController::class, 'preview'])->name('links.preview');

    Route::get('/users/search', UserSearchController::class)->name('users.search');

    Route::post('/shares', [ShareController::class, 'store'])->name('shares.store');
    Route::delete('/shares/{share}', [ShareController::class, 'destroy'])->name('shares.destroy');

    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::patch('/{id}/read', [NotificationController::class, 'markRead'])->name('read');
        Route::patch('/read-all', [NotificationController::class, 'markAllRead'])->name('readAll');
    });

    Route::prefix('shared')->name('shared.')->group(function () {
        Route::get('collections/{share}', [SharedCollectionController::class, 'show'])->name('collections.show');
        Route::post('collections/{share}/import', [SharedCollectionController::class, 'import'])->name('collections.import');
    });
});

require __DIR__.'/settings.php';
