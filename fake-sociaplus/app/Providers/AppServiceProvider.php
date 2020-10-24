<?php

namespace App\Providers;

use App\Services\MatchService;
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
        /**
         * Inject service to container
         * TODO: create new service provider for registering additional services
         */
        $this->app->singleton(MatchService::class, function($app) {
            return new MatchService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
