<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Kodeine\Acl\Traits\HasRole;

class User extends Authenticatable
{
    use HasRole;

    const PLAN_FREE = 'free';
    const PLAN_PREMIUM = 'premium';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','plan_type',
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
}
