<?php

namespace App\Providers;

use App\Contracts\VideoToGif;
use App\Services\GrabzitService;
use App\Clients\GrabzIt\GrabzItClient;
use Illuminate\Support\ServiceProvider;


class GrabzitProvider extends ServiceProvider
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
        $this->app->bind(VideoToGif::class, function($app) {
            return new GrabzitService(
                    new GrabzItClient(env("GRABZIT_KEY"), env("GRABZIT_SECRET"))
                );
        });
    }
}
