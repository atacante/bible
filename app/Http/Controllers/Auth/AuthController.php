<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ViewHelper;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Request;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['getLogout','getAdminLogout']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => 'required|max:255',
//            'username' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
            'coupon_code' => 'coupon_exist|coupon_expire|coupon_uses',
            'g-recaptcha-response' => 'required|captcha',
            'card_number' => 'numeric',
        ];

        if(Input::get('plan_type') == User::PLAN_PREMIUM){
            $rules['plan_name'] = 'required';
        }

        return Validator::make($data, $rules,
        [
            'g-recaptcha-response.required' => 'The recaptcha field is required.',
            'g-recaptcha-response.captcha'  => 'The recaptcha field is required.',
        ]);

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'plan_type' => $data['plan_type'],
            'invited_by_id' => $data['invited_by_id'],
        ]);

        if($user){
            if(Input::get('plan_type') == User::PLAN_PREMIUM){
                $user->upgradeToPremium(Input::get('plan_name'));
            }else{
                $user->downgradeToFree();
            }
            $user->assignRole(Config::get('app.role.user'));
        }

        return $user;
    }

    public function getLogin()
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        return $this->showLoginForm();
    }

    public function getRegister()
    {
        $invited_by_id = Input::old('invited_by_id');
        if($invite = Input::get('invite')){
            $invited_by_id = $invite;
        }
        if($invited_by_id){
            $user = User::find($invited_by_id);
            Session::flash('inviter_id', $user->id);
            Session::flash('inviter_name', $user->name);
        }
        return $this->showRegistrationForm();
    }

    public function getLogout()
    {
        Auth::Logout();
        Session::flush();
        return redirect('/');
    }

    public function getAdminLogout()
    {
        Auth::Logout();
        $adminId = Session::get('adminId');
        $adminBackUrl = Session::get('adminBackUrl');
        $adminAuthToken = Session::get('adminAuthToken');
        $admin = User::where('id', $adminId)->where('auth_token', $adminAuthToken)->first();
        Session::flush();
        if($admin){
            Auth::login($admin, $remember = true);
        }
        return redirect($adminBackUrl?$adminBackUrl:ViewHelper::adminUrlSegment());
    }

//    protected function authenticated(\Illuminate\Http\Request $request, User $user)
//    {
//        return redirect()->intended('/dashboard');
//    }
}
