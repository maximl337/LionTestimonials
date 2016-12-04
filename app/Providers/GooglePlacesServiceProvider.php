<?php

namespace App\Providers;

use GuzzleHttp\Client;
use App\Contracts\GooglePlaces;
use App\Services\GooglePlacesService;
use Illuminate\Support\ServiceProvider;

class GooglePlacesServiceProvider extends ServiceProvider
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
        $this->app->bind(GooglePlaces::class, function($app) {
            return new GooglePlacesService(new Client);
        });
    }
}
