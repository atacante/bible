<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;

class Group extends BaseModel
{
    const ACCESS_PUBLIC = 'public';
    const ACCESS_SECRET = 'private';
    const ACCESS_PUBLIC_MEMBERS = 'public_members';

    public $timestamps = true;

    protected $table = 'groups';
    protected $fillable = ['id','owner_id','category_id','group_name','group_desc','group_email','group_image','access_level'];

    protected $dates = ['created_at', 'updated_at'];

    public function rules()
    {
        $rules =  [
            'group_name' => 'required',
            'group_desc' => 'required',
//            'group_email' => 'required|unique:groups',
        ];

        switch(Request::method())
        {
            case 'PUT':
            {
                $rules['group_email'] = 'required|unique:groups,group_email,'.$this->id;
            }
                break;
        }

        return $rules;
    }

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function members() {
        $members = $this->belongsToMany(User::class, 'groups_users', 'group_id', 'user_id')->withTimestamps();
        $members->where('banned',false)->where('approved',true);
//        if(Input::get('type') == 'banned'){
//            $members->where('banned',true);
//        }
//        else{
//            $members->where('banned',false);
//        }
        return $members;
    }

    public function userRequests() {
        return $this->belongsToMany(User::class, 'groups_users', 'group_id', 'user_id')
            ->where('banned',false)
            ->where('approved',false)
            ->withTimestamps();
    }

    public function joinRequests()
    {
        return $this->morphToMany('App\User', 'connect_requests');
    }

    public function notes()
    {
        return $this->morphedByMany('App\Note', 'groups_shares');
    }

    public function journals()
    {
        return $this->morphedByMany('App\Journal', 'groups_shares');
    }

    public function prayers()
    {
        return $this->morphedByMany('App\Prayer', 'groups_shares');
    }

    public function checkUserBanned($userId = false)
    {
        if($userId){
            $user = User::find($userId);
        }
        else{
            $user = Auth::user();
        }

        if($user){
            return in_array($this->id,$user->groupsBanned->modelKeys());
        }
        return false;
    }
}
