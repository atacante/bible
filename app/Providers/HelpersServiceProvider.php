<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelpersServiceProvider extends ServiceProvider
{

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register
     *
     * @return void
     */
    public function register()
    {
        foreach (glob(app_path().'/Http/Helpers/*.php') as $filename) {
            require_once($filename);
        }
    }
}
