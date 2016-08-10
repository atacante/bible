<?php

namespace App\Http\Controllers;

use App\Contact;
use App\CmsPage;
//use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Krucas\Notification\Facades\Notification;

class SiteController extends Controller
{
    public function getHome()
    {
        return view('site.home', []);
    }

    public function getAbout()
    {
        $model = CmsPage::where('system_name', '=', 'about')->take(1)->get();
        return view('site.about', ['page'=>$model[0]]);
    }

    public function anyContact(\Illuminate\Http\Request $request)
    {
        $model = new Contact();
        if (Request::isMethod('post')) {
            $this->validate($request, $model->rules(),$model->messages());
            $contactEmail = Input::get('email');//Config::get('app.contactEmail')
            $send = Mail::send('emails.contact', Input::all(), function($message) use($contactEmail)
            {
                $message->to($contactEmail)->subject('Contact From Request');
            });
            if($send){
                Notification::successInstant('Message has been successfully sent');
            }
        }
        return view('site.contact', $model);
    }

    public function getFaq()
    {
        return view('site.faq', []);
    }
}
