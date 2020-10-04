<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Competition;
use App\Models\CompetitionObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Competition::observe(CompetitionObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
