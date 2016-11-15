<?php

namespace App\Providers;

use View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('filters', 'App\Http\Composers\BibleFiltersComposer');
        View::composer('filters', 'App\Http\Composers\UserFiltersComposer');
        View::composer('filters', 'App\Http\Composers\NotesFiltersComposer');
    }

    /**
     * Register
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
