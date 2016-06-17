<?php

namespace App\Providers;

use App\Coupon;
use App\Journal;
use App\LexiconBerean;
use App\LexiconKjv;
use App\LexiconNasb;
use App\Location;
use App\Note;
use App\People;
use App\Prayer;
use App\User;
use App\Validators\CheckCouponValidator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;
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

        LexiconKjv::saving(function($model)
        {
            if($model->isDirty('symbolism')){
                $model->symbolism_updated_at = Carbon::now();
            }
            return true;
        });

        LexiconKjv::saved(function($model)
        {
            if($model->isDirty('symbolism')){
                $model->cacheSymbolismForBeginnerMode();
            }
            return true; //if false the model wont save!
        });

        LexiconBerean::saving(function($model)
        {
            if($model->isDirty('symbolism')){
                $model->symbolism_updated_at = Carbon::now();
            }
            return true;
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

        LexiconNasb::saving(function($model)
        {
            if($model->isDirty('symbolism')){
                $model->symbolism_updated_at = Carbon::now();
            }
            return true;
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

            if($model->isDirty('plan_type')){
                if($model->plan_type == $model::PLAN_PREMIUM){
//                    $model->upgraded_at = Carbon::now();
                }
            }

            return true; //if false the model wont save!
        });

        User::saved(function($model)
        {
            if($role = Request::input('role',false)){
                $model->syncRoles($role);
            }

            if($model->isDirty('plan_type')){
                if($model->plan_type == $model::PLAN_PREMIUM){
                    $model->upgradeToPremium();
                }
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

        Coupon::saving(function($model)
        {
            if($model->exists && $model->isDirty('used') && $model->uses_limit == $model->used){
                $model->status = false;
            }
            if($model->isDirty('uses_limit') && $model->uses_limit > $model->used){
                $model->status = true;
            }
            return true; //if false the model wont save!
        });

        Note::saving(function($model)
        {
            if($model->isDirty('access_level') && $model->access_level != Note::ACCESS_PRIVATE){
                $model->published_at = Carbon::now();
            }
            return true; //if false the model wont save!
        });

        Journal::saving(function($model)
        {
            if($model->isDirty('access_level') && $model->access_level != Journal::ACCESS_PRIVATE){
                $model->published_at = Carbon::now();
            }
            return true; //if false the model wont save!
        });

        Prayer::saving(function($model)
        {
            if($model->isDirty('access_level') && $model->access_level != Prayer::ACCESS_PRIVATE){
                $model->published_at = Carbon::now();
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
