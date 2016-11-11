<?php

namespace App;

use App\Helpers\NotificationsHelper;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
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

    const BOOKMARK_CHAPTER = 'chapter';
    const BOOKMARK_VERSE = 'verse';

    const BETA_TESTERS_LIMIT = 1000;
    const BETA_TESTERS_LIMIT_MSG = 'Our Beta Testing team is currently filled. If you would like to be notified when we complete our beta phase please register to receive our newsletter.';

//    public $coupon_code;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','lastname', 'email', 'password','plan_type','about_me','avatar','invited_by_id', 'subscribed','church_name','country_id','state','city','last_reader_url'
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

    protected static function boot() {
        parent::boot();
        static::saved(function($user) {
            if(!$user->getOriginal('id') && !$user->notificationsSettings){
                $user->notificationsSettings()->create([]);
            }
        });
        static::deleting(function($user) {
            $user->notes()->delete();
            $user->journals()->delete();
            $user->prayers()->delete();
            $user->statuses()->delete();
            $user->myGroups()->delete();
            $user->joinedGroups()->detach();
            $user->friends()->detach();
            $user->requests()->detach();
            $user->notesLikes()->detach();
            $user->journalLikes()->detach();
            $user->prayersLikes()->detach();
            $user->statusesLikes()->detach();
            $user->notesReports()->detach();
            $user->journalReports()->detach();
            $user->prayersReports()->detach();
            $user->statusesReports()->detach();
            $user->friendRequests()->detach();
            $user->groupsRequests()->detach();
            $user->readerViews()->delete();
            $user->lexiconViews()->delete();
            $user->strongsViews()->delete();
            $user->blogViews()->delete();
            $user->notificationsSettings()->delete();
            return true;
        });
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
//            'role' => 'required',
//            'username' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
            'card_number' => 'numeric',
            'coupon_code' => 'coupon_exist|coupon_expire|coupon_uses|coupon_user_uses'
        ];

        if(Auth::check() && Auth::user()->is(Config::get('app.role.admin')) && $this->plan_type == self::PLAN_FREE && Request::input('plan_type') != self::PLAN_FREE){
            $rules['coupon_code'] = 'required|coupon_exist|coupon_expire|coupon_uses|coupon_user_uses';
        }

        switch(Request::method())
        {
            case 'PUT':
            {
                $rules['email'] = 'required|email|max:255|unique:users,email,'.$this->id;
                if((Input::get('plan_type') == self::PLAN_PREMIUM)
                    && !$this->isPremium()
                    && !$this->onPlan(Input::get('plan_name'))
                ){
                    $rules['plan_name'] = 'required';
                    if(!$this->hasPaymentAccount() && !Input::get('coupon_code', false)){
                        $rules['card_number'] = 'required|numeric';
                        $rules['card_expiration'] = 'required';
                        $rules['billing_name'] = 'required';
                        $rules['billing_address'] = 'required';
                        $rules['billing_zip'] = 'required';
                    }
                }else{
                    if(Input::get('card_number') || Input::get('card_expiration')){
                        $rules['card_expiration'] = 'required';
                        $rules['card_number'] = 'required|numeric';
                        $rules['billing_name'] = 'required';
                        $rules['billing_address'] = 'required';
                        $rules['billing_zip'] = 'required';
                    }
                }
            }
                break;
        }

        return $rules;
    }

    public function bookmarks($model)
    {
        return $this->morphedByMany($model,'item','bookmarks')->withPivot('bookmark_type', 'bible_version')/*->orderBy('bookmarks.created_at','desc')*/;
    }

    public function bookmarksAmericanKingJames()
    {
        return $this->morphedByMany(VersesAmericanKingJamesEn::class,'item','bookmarks')->withPivot('bookmark_type', 'bible_version')/*->orderBy('bookmarks.created_at','desc')*/;
    }

    public function notificationsSettings() {
        return $this->hasOne(NotificationsSettings::class, 'user_id', 'id');
    }

    public function country() {
        return $this->hasOne(Country::class, 'id', 'country_id');
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

    public function journals()
    {
        return $this->hasMany(Journal::class, 'user_id', 'id');
    }

    public function prayers()
    {
        return $this->hasMany(Prayer::class, 'user_id', 'id');
    }

    public function statuses()
    {
        return $this->hasMany(WallPost::class, 'user_id', 'id');
    }

    public function highlights()
    {
        return $this->hasMany(Highlight::class, 'user_id', 'id');
    }

    public function myGroups()
    {
        return $this->hasMany(Group::class, 'owner_id', 'id');
    }

    public function myGroupsRequests($withBanned = false)
    {
        $groups = $this->belongsToMany(Group::class, 'groups_users', 'user_id', 'group_id')->where('approved',false);
        if(!$withBanned){
            $groups->where('banned',$withBanned);
        }
        return $groups->withTimestamps();
    }

    public function joinedGroups($withBanned = false)
    {
        $groups = $this->belongsToMany(Group::class, 'groups_users', 'user_id', 'group_id')->where('approved',true);
        if(!$withBanned){
            $groups->where('banned',$withBanned);
        }
        return $groups->withTimestamps();
    }

    public function groupsBanned()
    {
        return $this->belongsToMany(Group::class, 'groups_users', 'user_id', 'group_id')->where('banned',true)->withTimestamps();
    }

    public function joinGroup(Group $group)
    {
        $data = [];
        if($group->access_level != Group::ACCESS_SECRET || $group->joinRequests()->where('user_id',$this->id)->first()){
            $data['approved'] = true;
        }
        else{
            NotificationsHelper::groupRequest($group);
        }
        $this->joinedGroups()->attach($group->id,$data);
        $group->joinRequests()->detach($this->id);
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

    public function readerViews()
    {
        return $this->hasMany(UsersViews::class, 'user_id', 'id')->with('item')->where('item_category',UsersViews::CAT_READER);
    }

    public function lexiconViews()
    {
        return $this->hasMany(UsersViews::class, 'user_id', 'id')->with('item')->where('item_category',UsersViews::CAT_LEXICON);
    }

    public function strongsViews()
    {
        return $this->hasMany(UsersViews::class, 'user_id', 'id')->with('item')->where('item_category',UsersViews::CAT_STRONGS);
    }

    public function blogViews()
    {
        return $this->hasMany(UsersViews::class, 'user_id', 'id')->with(['item','item.category'])->where('item_category',UsersViews::CAT_BLOG);
    }

    public function userMeta()
    {
        return $this->hasOne(UsersMeta::class, 'user_id', 'id')->orderBy('id', SORT_DESC);
    }

    public function followFriend(User $user)
    {
        $this->friends()->attach($user->id);
        NotificationsHelper::friendRequest($user);
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
            $coupon = Coupon::getCoupon($coupon_code);
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

            if(!$this->isOnCoupon()){
                $this->subscription()->cancel();
            }

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

    public function checkNotifTooltip($type)
    {
        $notifSettings = $this->notificationsSettings;
        if(!$notifSettings){
            $notifSettings = $this->notificationsSettings()->create([]);
        }
        return !$notifSettings->$type;
    }

    public function setNotifTooltip($type)
    {
        $notifSettings = $this->notificationsSettings;
        if(!$notifSettings){
            $notifSettings = $this->notificationsSettings()->create([]);
        }
        $notifSettings->$type = true;
        $notifSettings->save();
    }

    public function setCountryIdAttribute($value)
    {
        if(empty($value)){
            $this->attributes['country_id'] = NULL;
        }else{
            $this->attributes['country_id'] = $value;
        }
    }

    public function setInvitedByIdAttribute($value)
    {
        if(empty($value)){
            $this->attributes['invited_by_id'] = NULL;
        }else{
            $this->attributes['invited_by_id'] = $value;
        }
    }

    public static function adminEmails()
    {
        return self::whereHas('roles', function ($q) {
            $q->whereIn('slug',[Config::get('app.role.admin')]);
        })->pluck('email')->toArray();
    }

    public static function checkBetaTestersLimit()
    {
        $betaMode = CmsPage::where('system_name','beta_mode')->first();

        $limit = self::BETA_TESTERS_LIMIT;
        $msg = self::BETA_TESTERS_LIMIT_MSG;
        if($betaMode->meta_title){
            $limit = $betaMode->meta_title;
        }
        if($betaMode->meta_description){
            $msg = $betaMode->meta_description;
        }

        $usersCount = self::whereHas('roles', function ($q) {
            $q->whereIn('slug',[Config::get('app.role.user')]);
        })->count();
        if($usersCount <= $limit){
            return false;
        }
        return $msg;
    }

    public function getNameAttribute($value){
        return $value.' '.$this->lastname;
    }

    public function getRawName(){
        return str_replace(' '.$this->lastname,'',$this->name);
    }

}
