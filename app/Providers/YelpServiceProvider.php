<?php

namespace App\Providers;

use App\Contracts\Yelp;
use GuzzleHttp\Client;
use App\Services\YelpService;
use Illuminate\Support\ServiceProvider;

class YelpServiceProvider extends ServiceProvider
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
        $this->app->bind(Yelp::class, function($app) {
            return new YelpService(new Client);
        });
    }
}
