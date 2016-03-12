<?php

namespace App\Providers;

use App\LexiconKjv;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*LexiconKjv::saving(function($model)
        {
            if($model->isDirty('verse_part')){
                var_dump($model->getDirty());
//                $model->updateCache();
            }
            return true; //if false the model wont save! 
        });*/
        LexiconKjv::saved(function($model)
        {
            if($model->isDirty('verse_part')){
                $model->cacheVerse();
            }
            return true; //if false the model wont save!
        });
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
