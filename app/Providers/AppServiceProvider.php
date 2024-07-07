<?php

namespace App\Providers;

use App\Guards\JwtGuard;
use App\Pagination\SimplePaginator;
use App\Services\AccountService;
use App\Services\BrandService;
use App\Services\JwtService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Pagination\LengthAwarePaginator;
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
            return new JwtService(
                config('app.url'),
                storage_path('keys/private_key.pem'),
                storage_path('keys/public_key.pem')
            );
        });

        $this->app->singleton(AccountService::class, function (Application $app) {
            return new AccountService($app->make(JwtService::class));
        });

        $this->app->singleton(BrandService::class, function (Application $app) {
            return new BrandService();
        });

        $this->registerPaginators();
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

    protected function registerPaginators()
    {
        $this->app->bind(LengthAwarePaginator::class, SimplePaginator::class);
    }
}
