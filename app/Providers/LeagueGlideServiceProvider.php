<?php

namespace App\Providers;

use App\Supports\LeagueGlide;
use Illuminate\Support\ServiceProvider;

class LeagueGlideServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('glide', function () {
            return new LeagueGlide();
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
