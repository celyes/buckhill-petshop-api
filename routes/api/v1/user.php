<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login']);
Route::post('/create', [UserController::class, 'create']);

Route::middleware('jwt')->group(function () {
    Route::put('/edit', [UserController::class, 'edit']);
    Route::get('/logout', [UserController::class, 'logout']);
});
