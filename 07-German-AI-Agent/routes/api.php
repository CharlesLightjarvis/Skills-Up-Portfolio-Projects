<?php

use App\Http\Controllers\CahierDeChargeController;
use Illuminate\Support\Facades\Route;

Route::post('/cahier-de-charge', [CahierDeChargeController::class, 'store']);
Route::get('/cahier-de-charge/{jobId}/status', [CahierDeChargeController::class, 'status']);
Route::get('/cahier-de-charge/{jobId}/download', [CahierDeChargeController::class, 'download']);
