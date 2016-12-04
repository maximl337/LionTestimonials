<?php

namespace App\Providers;

use App\Contracts\Yelp;
use App\Contracts\ExternalVendor;
use App\Services\ExternalVendorService;
use Illuminate\Support\ServiceProvider;

class ExternalVendorServiceProvider extends ServiceProvider
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
        $this->app->bind(ExternalVendor::class, function($app) {
            return new ExternalVendorService($app->make('App\Contracts\Yelp'), $app->make('App\Contracts\GooglePlaces'));
        });
    }
}
