<?php

namespace App\Providers;

use App\Guards\JwtGuard;
use App\Services\AccountService;
use App\Services\JwtService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(JwtService::class, function (Application $app) {
            return new JwtService();
        });

        $this->app->singleton(AccountService::class, function (Application $app) {
            return new AccountService($app->make(JwtService::class));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::extend('jwt', function (Application $app, string $name, array $config) {
            return new JwtGuard(
                Auth::createUserProvider($config['provider']),
                $app->make(JwtService::class)
            );
        });
    }
}
