<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Coupon extends BaseModel
{
    public $timestamps = true;

    protected $table = 'coupons';
    protected $fillable = ['id','user_id','member_type','coupon_type','status','amount','coupon_code','uses_limit','uses','expire_at'];

    protected $dates = ['created_at', 'updated_at', 'expire_at'];

    public function rules()
    {
        $rules =  [
            'coupon_code' => 'required|unique:coupons',
            'expire_at' => 'date',
            'amount' => 'required|numeric',
        ];

        switch(Request::method())
        {
            case 'PUT':
            {
                $rules['coupon_code'] = 'required|unique:coupons,coupon_code,'.$this->id;
            }
                break;
        }

        return $rules;
    }

    public static $columns = [
        "Coupon Code"=>false,
        "Coupon Type"=>false,
        "Status"=>false,
        "Amount"=>false,
        "Expiration"=>false,
        "# of uses"=>false,
        "Already Used"=>false,
        "Created"=>"created_at"
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function setUsesLimitAttribute($value)
    {
        $this->attributes['uses_limit'] = $value?$value:null;
    }

    public function setExpireAtAttribute($value)
    {
        $this->attributes['expire_at'] = $value?Carbon::createFromTimestamp(strtotime($value)):null;
    }
}
