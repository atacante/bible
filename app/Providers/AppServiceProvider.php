<?php

namespace App\Providers;

use App\LexiconBerean;
use App\LexiconKjv;
use App\LexiconNasb;
use App\Location;
use App\People;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
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

        LexiconKjv::saved(function($model)
        {
            if($model->isDirty('symbolism')){
                $model->cacheSymbolismForBeginnerMode();
            }
            return true; //if false the model wont save!
        });

        LexiconBerean::saved(function($model)
        {
            if($model->isDirty('verse_part')){
                $model->cacheVerse();
            }
            return true; //if false the model wont save!
        });

        LexiconBerean::saved(function($model)
        {
            if($model->isDirty('symbolism')){
                $model->cacheSymbolismForBeginnerMode();
            }
            return true; //if false the model wont save!
        });

        LexiconNasb::saved(function($model)
        {
            if($model->isDirty('verse_part')){
                $model->cacheVerse();
            }
            return true; //if false the model wont save!
        });

        LexiconNasb::saved(function($model)
        {
            if($model->isDirty('symbolism')){
                $model->cacheSymbolismForBeginnerMode();
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

        People::saved(function($model)
        {
            if($model->isDirty('people_name') && !$model->isDirty('associate_verses') && $model->associate_verses){
                $model->associateVerses('detach');
                $model->associateVerses('attach');
            }
            if($model->isDirty('associate_verses')){
                if($model->associate_verses){
                    $model->associateVerses('attach');
                }
                else{
                    $model->associateVerses('detach');
                }
            }
        });
        People::deleted(function($model)
        {
            $model->verses()->sync([]);
        });

        Location::saved(function($model)
        {
            if($model->isDirty('location_name') && !$model->isDirty('associate_verses') && $model->associate_verses){
                $model->associateVerses('detach');
                $model->associateVerses('attach');
            }
            if($model->isDirty('associate_verses')){
                if($model->associate_verses){
                    $model->associateVerses('attach');
                }
                else{
                    $model->associateVerses('detach');
                }
            }
        });
        Location::deleted(function($model)
        {
            $model->verses()->sync([]);
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
