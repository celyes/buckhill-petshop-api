<?php


use App\Http\Controllers\BrandController;
use Illuminate\Support\Facades\Route;


Route::get('/', [BrandController::class, 'index']);

Route::middleware(['jwt', 'verified'])->group(function () {
    Route::post('/create', [BrandController::class, 'create']);
});
