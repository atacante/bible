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
    public function anyProfile(\Illuminate\Http\Request $request)
    {
        $user = User::find(Auth::user()->id);

        if($inputs = Input::old()){
            $user->fill($inputs);
        }

        if (Request::isMethod('put')) {
            $this->validate($request, $user->rules());
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
