<?php

namespace Lupennat\List;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class ListServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('list', __DIR__ . '/../dist/js/list.js');
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
