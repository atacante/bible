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
use App\Providers\CustomAuthorizeNet\Billable;

class User extends Authenticatable
{
    use HasRole;
    use Billable;

    const PLAN_FREE = 'free';
    const PLAN_PREMIUM = 'premium';

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

    protected $casts = [
        'banned' => 'boolean',
    ];

    public function rules()
    {
        $rules = [
            'name' => 'required|max:255',
//            'role' => 'required',
//            'username' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
            'card_number' => 'numeric',
            'coupon_code' => 'coupon_exist|coupon_expire|coupon_uses|coupon_user_uses'
        ];

        switch(Request::method())
        {
            case 'PUT':
            {
                $rules['email'] = 'required|email|max:255|unique:users,email,'.$this->id;
                if(Input::get('plan_type') == self::PLAN_PREMIUM){
                    $rules['plan_name'] = 'required';
                }
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
        return $this->belongsToMany(Group::class, 'groups_users', 'user_id', 'group_id')->where('banned',false)->withTimestamps();
    }

    public function groupsBanned()
    {
        return $this->belongsToMany(Group::class, 'groups_users', 'user_id', 'group_id')->where('banned',true)->withTimestamps();
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

    public function followers()
    {
        return $this->belongsToMany(User::class, 'users_friends', 'friend_id', 'user_id')->withTimestamps();
    }

    public function friendRequests()
    {
        return $this->morphToMany('App\User', 'connect_requests');
    }

    public function groupsRequests()
    {
        return $this->morphedByMany('App\Group', 'connect_requests'/*,'connect_requests','user_id','connect_requests_id'*/);
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

    public function upgradeToPremium($plan_name)
    {
        $premiumCost = self::getPremiumCost($plan_name);
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

        $result = $this->createAccountAndOrSubscribe($plan_name, $premiumCost);

        if($result['subscription']['success']){
            $this->upgraded_at = Carbon::now();
            $this->save();
        }elseif(!empty($result['subscription']['message'])){
            $this->plan_type = self::PLAN_FREE;
            $this->save();
        }
    }

    public function downgradeToFree()
    {
        if($this->subscription()){
            $this->subscription()->cancel();

            if($this->subscription()->onGracePeriod()){
                $this->plan_type = self::PLAN_PREMIUM;
                $this->save();
                $message = 'Your will be moved to free account at '.date_format($this->subscription()->ends_at, 'Y-m-d');

                if(Request::is('user/profile')){
                    Notification::successInstant($message);
                }
                else{
                    Notification::success($message);
                }
            }
        }
    }

    public function isPremiumPaid()
    {
        foreach(self::getPossiblePlans() as $plan_name => $plan ){
            if($this->onPlan($plan_name)){
                return true;
            }
        }

        return false;
    }

    public function isBanned($type,$id)
    {
        switch($type){
            case 'group':
                return in_array($id,$this->groupsBanned->modelKeys());
        }
        return false;
    }

    public function getActivePlan(){
        if($this->subscription()){
            return $this->subscription()->authorize_plan;
        }
    }
}
