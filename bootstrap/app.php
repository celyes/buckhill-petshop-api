<?php

use App\Http\Middleware\VerifyJwtToken;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        using: function () {
            Route::middleware('api')
                ->prefix('api/v1')
                ->as('api.v1.')
                ->group(function () {
                    $routePrefixes = collect(scandir(base_path('routes/api/v1/')))
                        ->slice(2)
                        ->values()
                        ->map(fn ($file) => explode('.', $file)[0])
                        ->toArray();

                    foreach ($routePrefixes as $prefix) {
                        Route::prefix("/{$prefix}")
                            ->as("{$prefix}.")
                            ->group(base_path("routes/api/v1/{$prefix}.php"));
                    }
                });

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        },
        commands: __DIR__.'/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'jwt' => VerifyJwtToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {})->create();
