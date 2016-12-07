<?php

namespace App\Providers;

use GuzzleHttp\Client;
use App\Contracts\MicrosoftApi;
use App\Services\MicrosoftApiService;
use Illuminate\Support\ServiceProvider;

class MicrosoftApiServiceProvider extends ServiceProvider
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
        $this->app->bind(MicrosoftApi::class, function($app) {
            return new MicrosoftApiService(new Client);
        });
    }
}
