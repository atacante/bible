<?php

namespace App\Validators;

use App\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class CustomValidator{
    public function validateCouponExist($attribute, $value, $parameters)
    {
        $coupon = Coupon::where('coupon_code', $value)->first();
        if($coupon && $coupon->user_id > 0){
            return (Auth::check() && Auth::user()->id == $coupon->user_id);
        }
        return $coupon;
    }

    public function validateCouponExpire($attribute, $value, $parameters)
    {
        $coupon = Coupon::where('coupon_code', $value)->first();
        return ($coupon && (!$coupon->expire_at || strtotime($coupon->expire_at) > time()));
    }

    public function validateCouponUses($attribute, $value, $parameters)
    {
        $coupon = Coupon::where('coupon_code', $value)->first();
        return ($coupon && (!$coupon->uses_limit || $coupon->used < $coupon->uses_limit));
    }
}