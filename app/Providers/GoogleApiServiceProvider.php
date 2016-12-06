<?php

namespace App\Providers;

use GuzzleHttp\Client;
use App\Contracts\GoogleApi;
use App\Services\GoogleApiService;
use Illuminate\Support\ServiceProvider;

class GoogleApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(GoogleApi::class, function($app) {
            return new GoogleApiService(new Client);
        });
    }
}
