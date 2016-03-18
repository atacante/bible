<?php

namespace App\Http\Controllers;

use App\Helpers\ViewHelper;
use App\User;
//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use \Illuminate\Support\Facades\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Kodeine\Acl\Models\Eloquent\Role;
use Krucas\Notification\Facades\Notification;
use Validator;


class UserController extends Controller
{
    private function validator($request,$user)
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
                $rules['email'] = 'required|email|max:255|unique:users,email,'.$user->id;
            }
                break;
            case 'POST':
                /*{
                    $rules['password'] = 'required|confirmed|min:6';
                    $rules['password_confirmation'] = 'required';
                }
                break;*/
            case 'GET':
            case 'DELETE':
            default:
                break;
        }
        $this->validate($request, $rules);
    }

    public function anyProfile(\Illuminate\Http\Request $request)
    {
        $user = User::find(Auth::user()->id);
        if (Request::isMethod('put')) {
            $this->validator($request,$user);
            if($user->update(Input::all())){
                Notification::successInstant('Your profile info successfully saved');
            }
        }
        return view('user.profile',
            [
                'model' => $user,
                'page_title' => 'My Profile',
            ]);
    }
}
