<?php

namespace App\Providers;

use App\Contracts\TestimonialInterface;
use App\Services\TestimonialService;
use Illuminate\Support\ServiceProvider;

class TestimonialServiceProvider extends ServiceProvider
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
        $this->app->bind(TestimonialInterface::class, function($app) {
            return new TestimonialService;
        });
    }
}
