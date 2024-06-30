<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', function () {
   return 'Logging the user in...';
});

Route::post('/create', function () {
    return response('Hello World', 201)
        ->header('Content-Type', 'text/plain');
});
