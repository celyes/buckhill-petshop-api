<?php

use App\Http\Controllers\BrandController;
use Illuminate\Support\Facades\Route;

Route::get('/{brand}', [BrandController::class, 'show']);

Route::middleware(['jwt', 'verified'])->group(function () {
    Route::post('/create', [BrandController::class, 'create']);
    Route::put('/{brand}', [BrandController::class, 'edit']);
    Route::delete('/{brand}', [BrandController::class, 'delete']);
});
