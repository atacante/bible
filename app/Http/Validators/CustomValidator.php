<?php

namespace App\Validators;

use App\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Validator;

class CustomValidator{
    public function validateCouponExist($attribute, $value, $parameters)
    {
        $coupon = Coupon::getCoupon($value);
        if($coupon && $coupon->member_type){
            if(Auth::check()){
                if(!Auth::user()->is($coupon->member_type)){
                    return false;
                }
            }
            else{
                if($coupon->member_type != 'user'){
                    return false;
                }
            }
        }
        if($coupon && $coupon->user_id > 0){
            return (Auth::check() && Auth::user()->id == $coupon->user_id);
        }
        return $coupon;
    }

    public function validateCouponExpire($attribute, $value, $parameters)
    {
        $coupon = Coupon::getCoupon($value);
        return ($coupon && (!$coupon->expire_at || strtotime($coupon->expire_at) > time()));
    }

    public function validateCouponUses($attribute, $value, $parameters)
    {
        $coupon = Coupon::getCoupon($value);
        return ($coupon && (!$coupon->uses_limit || $coupon->used < $coupon->uses_limit));
    }

    public function validateCouponUserUses($attribute, $value, $parameters)
    {
        $coupon = Coupon::getCoupon($value);
        $couponUser = false;
        if($coupon){
            $couponUser = $coupon->users()->wherePivot('is_used',true)->wherePivot('user_id',Auth::user()->id)->first();
        }
        return ($coupon && ($coupon->is_permanent || !$couponUser));
    }

    public function validateCouponUnique($attribute, $value, $parameters)
    {
        if($parameters && $parameters[0]){
            $coupon = Coupon::where('coupon_code', 'ILIKE', $value)->where('id','!=',$parameters[0])->first();
        }else{
            $coupon = Coupon::getCoupon($value);
        }

        if(!$coupon){
            return true;
        }

        return false;
    }
}