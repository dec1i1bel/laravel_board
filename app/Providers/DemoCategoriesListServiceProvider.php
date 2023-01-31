<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Lib\DemoService\DemoCategoriesList;

class DemoCategoriesListServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DemoCategoriesList::class, function($app) {
            return new DemoCategoriesList();
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
