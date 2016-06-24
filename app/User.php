<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Kodeine\Acl\Traits\HasRole;
use Krucas\Notification\Facades\Notification;

class User extends Authenticatable
{
    use HasRole;

    const PLAN_FREE = 'free';
    const PLAN_PREMIUM = 'premium';
    const PLAN_PREMIUM_COST = 100;
    const PLAN_PREMIUM_PERIOD = 30;

//    public $coupon_code;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','plan_type','about_me','avatar'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['created_at', 'updated_at', 'last_login_at','upgraded_at'];

    public function rules()
    {
        $rules = [
            'name' => 'required|max:255',
//            'role' => 'required',
//            'username' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
            'coupon_code' => 'coupon_exist|coupon_expire|coupon_uses|coupon_user_uses'
        ];

        switch(Request::method())
        {
            case 'PUT':
            {
                $rules['email'] = 'required|email|max:255|unique:users,email,'.$this->id;
            }
                break;
        }

        return $rules;
    }

    public function myGroups()
    {
        return $this->hasMany(Group::class, 'owner_id', 'id');
    }

    public function joinedGroups()
    {
        return $this->belongsToMany(Group::class, 'groups_users', 'user_id', 'group_id')->withTimestamps();
    }

    public function joinGroup(Group $group)
    {
        $this->joinedGroups()->attach($group->id);
    }

    public function leaveGroup(Group $group)
    {
        $this->joinedGroups()->detach($group->id);
    }

    public function friends()
    {
        return $this->belongsToMany(User::class, 'users_friends', 'user_id', 'friend_id')->withTimestamps();
    }

    public function followFriend(User $user)
    {
        $this->friends()->attach($user->id);
    }

    public function removeFriend(User $user)
    {
        $this->friends()->detach($user->id);
    }

    public function isOnline()
    {
        $isOnline = Cache::has('user-is-online-' . $this->id);
        if($isOnline){
            $this->is_online = true;
        }
        else{
            $this->is_online = false;
        }
        $this->save();
        return $isOnline;
    }

    public function isPremium()
    {
        return $this->plan_type == self::PLAN_PREMIUM;
    }

    public function upgradeToPremium()
    {
        if(!$this->isPremiumPaid()){
            $premiumCost = self::PLAN_PREMIUM_COST;
            if($coupon_code = Input::get('coupon_code')){
                $coupon = Coupon::where('coupon_code', $coupon_code)->first();
                $premiumCost -= $coupon->amount;
                $coupon->used++;
                $coupon->save();
                $coupon->users()->detach($this->id);
                $coupon->users()->attach($this->id,['is_used' => true]);
                if(Request::is('user/profile')){
                    Notification::successInstant('Coupon was applied');
                }
                else{
                    Notification::success('Coupon was applied');
                }
            }
            $this->upgraded_at = Carbon::now();
            if($this->save()){
                if(Request::is('user/profile')){
                    Notification::successInstant('Your Payment ($'.$premiumCost.') Has Been Received, You Now Have A Premium Account.');
                }
                else{
                    Notification::success('Your Payment ($'.$premiumCost.') Has Been Received, You Now Have A Premium Account.');
                }
            }
        }
    }

    public function isPremiumPaid()
    {
        return (strtotime($this->upgraded_at)+self::PLAN_PREMIUM_PERIOD*86400) > time();
    }
}
