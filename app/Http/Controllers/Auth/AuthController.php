<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\ViewHelper;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Request;
use App\Http\Components\MailchimpComponent;


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
            'lastname' => 'required|max:255',
//            'username' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
            'password_confirmation' => 'required',
            'coupon_code' => 'coupon_exist|coupon_expire|coupon_uses',
            'g-recaptcha-response' => 'required|captcha'
        ];

        if(Input::get('plan_type') == User::PLAN_PREMIUM){
            $rules['plan_name'] = 'required';

            if(!Input::get('coupon_code', false)){
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
        $mess = '';
        if($data['subscribed']) {
            $mess = MailchimpComponent::addEmailToList($data['email']);
        }
        $data['mess'] = $mess;
        if ($mess != ''){
            Mail::send('emails.mailchimp', $data, function($message) use($data)
            {
                $message->to(User::adminEmails())->subject('Subscription Mailchimp Error');
            });
        }

        $user = User::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'email' => strtolower($data['email']),
            'password' => bcrypt($data['password']),
            'plan_type' => $data['plan_type'],
            'subscribed' => $data['subscribed'],
            'invited_by_id' => $data['invited_by_id']?$data['invited_by_id']:null,
            'country_id' => $data['country_id']?$data['country_id']:null,
            'state' => $data['state'],
            'city' => $data['city'],
            'church_name' => $data['church_name'],
        ]);

        if($user){
            $user->assignRole(Config::get('app.role.user'));

            if(Input::get('plan_type') == User::PLAN_PREMIUM){
                $user->upgradeToPremium(Input::get('plan_name'));
            }else{
                $user->downgradeToFree();
            }

            Mail::send('emails.welcome', ['user' => $user], function($message) use($user)
            {
                $message->to($user->email)->subject('Welcome to BSC');
            });
        }

        return $user;
    }

    public function authenticated()
    {
        if(Auth::check() && Auth::user()->is(Config::get('app.role.user')) && Auth::user()->last_reader_url){
            return redirect(Auth::user()->last_reader_url);
        }
        return redirect()->intended($this->redirectPath());
    }

    public function getLogin()
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $view = property_exists($this, 'loginView')
            ? $this->loginView : 'auth.authenticate';

        if (view()->exists($view)) {
            return view($view);
        }

        if(Request::ajax()){
            return view('auth.login_popup_form');
        }
        return view('auth.login');
    }

    protected function sendFailedLoginResponse(\Illuminate\Http\Request $request)
    {
        if (($request->ajax() && ! $request->pjax()) || $request->wantsJson()) {
            return new JsonResponse([$this->loginUsername() => [$this->getFailedLoginMessage()]], 422);
        }
        return redirect()->back()
            ->withInput($request->only($this->loginUsername(), 'remember'))
            ->withErrors([
                $this->loginUsername() => $this->getFailedLoginMessage(),
            ]);
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

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(\Illuminate\Http\Request $request)
    {
        if(User::checkBetaTestersLimit()){
            return redirect('auth/register');
        }

        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        Auth::guard($this->getGuard())->login($this->create($request->all()));

        return redirect()->intended($this->redirectPath());
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

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function getCredentials(\Illuminate\Http\Request  $request){
        $credentials =  $request->only($this->loginUsername(), 'password');

        // Client asks to lowercase email trying log in
        if(!empty($credentials['email'])){
            $credentials['email'] = strtolower($credentials['email']);
        }
        return $credentials;
    }
}
