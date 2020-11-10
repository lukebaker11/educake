<?php

namespace App\Providers;

use App\Services\GuzzleHttpService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(GuzzleHttpService::class, function() {
            return new GuzzleHttpService();
        });
    }
}
