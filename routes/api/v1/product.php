<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['jwt', 'verified'])->group(function () {
    Route::post('/create', [ProductController::class, 'create']);
});
