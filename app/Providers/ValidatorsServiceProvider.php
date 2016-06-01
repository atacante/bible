<?php

namespace App\Providers;

use App\Validators\CustomValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class ValidatorsServiceProvider extends ServiceProvider {

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        /*Validator::resolver(function($translator, $data, $rules, $messages)
        {
            return new CustomValidator($translator, $data, $rules, $messages);
        });*/
        Validator::extend('coupon_exist', 'App\Validators\CustomValidator@validateCouponExist');
        Validator::extend('coupon_expire', 'App\Validators\CustomValidator@validateCouponExpire');
        Validator::extend('coupon_uses', 'App\Validators\CustomValidator@validateCouponUses');
    }

    /**
     * Register
     *
     * @return void
     */
    public function register()
    {

    }
}