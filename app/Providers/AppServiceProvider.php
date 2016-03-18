<?php

namespace App\Providers;

use App\LexiconKjv;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Request;
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

        User::saving(function($model)
        {
            if($model->isDirty('password')){
                if (Hash::needsRehash($model->password)) {
                    $model->password = bcrypt($model->password);//Hash::make('plain-text')
                }
            }

            return true; //if false the model wont save!
        });
        User::saved(function($model)
        {
            if($role = Request::input('role',false)){
//                $model->assignRole($role);
                $model->syncRoles($role);
            }
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
