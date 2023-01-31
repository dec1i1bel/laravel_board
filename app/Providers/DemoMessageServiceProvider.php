<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Lib\DemoService\DemoContainerRegistered;
use App\Lib\DemoService\DemoContainerBoot;

class DemoMessageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(DemoContainerRegistered::class, function ($app) {
            return new DemoContainerRegistered();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton(DemoContainerBoot::class, function($app) {
            return new DemoContainerBoot();
        });
    }
}
