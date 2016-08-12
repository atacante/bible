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

    const DFORMAT = 'm/d/Y';

    const PLAN_FREE = 'free';
    const PLAN_PREMIUM = 'premium';

//    public $coupon_code;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','plan_type','about_me','avatar','invited_by_id', 'subscribed'
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
                    if(!$this->hasPaymentAccount()){
                        $rules['card_number'] = 'required|numeric';
                        $rules['card_expiration'] = 'required';
                    }
                }else{
                    if(Input::get('card_number') || Input::get('card_expiration')){
                        $rules['card_expiration'] = 'required';
                        $rules['card_number'] = 'required|numeric';
                    }
                }
            }
                break;
        }

        return $rules;
    }

    public function inviter() {
        return $this->belongsTo(User::class, 'invited_by_id', 'id');
    }

    public function invited() {
        return $this->hasMany(User::class, 'invited_by_id', 'id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'user_id', 'id');
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

    public function requests()
    {
        return $this->belongsToMany(User::class, 'users_friends', 'friend_id', 'user_id')->withTimestamps();
    }

    public function notesLikes()
    {
        return $this->morphedByMany('App\Note','item','wall_likes')->orderBy('wall_likes.created_at','desc');
    }

    public function journalLikes()
    {
        return $this->morphedByMany('App\Journal','item','wall_likes')->orderBy('wall_likes.created_at','desc');
    }

    public function prayersLikes()
    {
        return $this->morphedByMany('App\Prayer','item','wall_likes')->orderBy('wall_likes.created_at','desc');
    }

    public function statusesLikes()
    {
        return $this->morphedByMany('App\WallPost','item','wall_likes')->orderBy('wall_likes.created_at','desc');
    }

    public function notesReports()
    {
        return $this->morphedByMany('App\Note','item','content_reports')->orderBy('content_reports.created_at','desc');
    }

    public function journalReports()
    {
        return $this->morphedByMany('App\Journal','item','content_reports')->orderBy('content_reports.created_at','desc');
    }

    public function prayersReports()
    {
        return $this->morphedByMany('App\Prayer','item','content_reports')->orderBy('content_reports.created_at','desc');
    }

    public function statusesReports()
    {
        return $this->morphedByMany('App\WallPost','item','content_reports')->orderBy('content_reports.created_at','desc');
    }

    public function friendRequests()
    {
        return $this->morphToMany('App\User', 'connect_requests');
    }

    public function groupsRequests()
    {
        return $this->morphedByMany('App\Group', 'connect_requests'/*,'connect_requests','user_id','connect_requests_id'*/);
    }

    public function views()
    {
        return $this->morphedByMany('App\VersesAmericanKingJamesEn','item','users_views')->orderBy('wall_likes.created_at','desc');
    }

    public function followFriend(User $user)
    {
        $this->friends()->attach($user->id);
    }

    public function removeRequest(User $user)
    {
        $this->friends()->detach($user->id);
    }

    public function ignoreRequest(User $user)
    {
        $this->friends()->updateExistingPivot($user->id,['ignore' => true]);
    }

    public function removeFriend(User $user)
    {
        $this->friends()->detach($user->id);
        $user->friends()->detach($this->id);
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
            $this->plan_type = self::PLAN_PREMIUM;
            $this->upgrade_plan = null;
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

    public function getPlanExpiresAt(){
        if(!$this->isPremiumPaid()){
            return false;
        }

        if($this->subscription()->cancelled()){
            $carbonEnd = Carbon::createFromFormat(
                Carbon::DEFAULT_TO_STRING_FORMAT, $this->subscription()->ends_at
            );
        }else{
            $lastUpdate = $this->subscription()->updated_at;
            $days = $this->subscription()->getBillingDays();
            $carbonStart = Carbon::createFromFormat(
                Carbon::DEFAULT_TO_STRING_FORMAT, $lastUpdate
            );
            $carbonEnd = $carbonStart->addDays($days);
        }

        return $carbonEnd->format(self::DFORMAT);
    }
}
