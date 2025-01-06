<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CheckbookService;  // Make sure the correct namespace is imported

class CheckbookServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Bind the CheckbookService class to the container
        $this->app->singleton(CheckbookService::class, function ($app) {
            return new CheckbookService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
