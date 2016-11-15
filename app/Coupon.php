<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class Coupon extends BaseModel
{
    public $timestamps = true;

    protected $table = 'coupons';
    protected $fillable = ['id','user_id','member_type','coupon_type','status','amount','coupon_code','uses_limit','uses','expire_at','is_permanent'];

    protected $dates = ['created_at', 'updated_at', 'expire_at'];

    public function rules()
    {
        $rules =  [
            'coupon_code' => 'required|coupon_unique',
            'expire_at' => 'date',
            'amount' => 'required|numeric',
        ];

        switch (Request::method()) {
            case 'PUT':
            {
                $rules['coupon_code'] = 'required|coupon_unique:'.$this->id;
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

    public static function getCoupon($value)
    {
        return self::where('coupon_code', 'ILIKE', $value)->first();
    }

    public function user()
    {
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

    public function setCouponCodeAttribute($value)
    {
        $this->attributes['coupon_code'] = strtolower($value);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupons_users', 'coupon_id', 'user_id')->withPivot('is_used');
    }
}
