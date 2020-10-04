<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Creators\MenuCreator;
use App\Http\Creators\BreadcrumbsCreator;

use Auth;


class CreatorServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->creator(
            '*', 'App\Http\Creators\MenuCreator'
        );

        view()->creator(
            '*', 'App\Http\Creators\BreadcrumbsCreator'
        );

        view()->creator(
            '*', 'App\Http\Creators\NotificationCreator'
        );
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
       //
    }

}
