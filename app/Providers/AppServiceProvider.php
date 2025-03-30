<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Services\RoverService;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(RoverService::class, function ($app) {
            return new RoverService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
