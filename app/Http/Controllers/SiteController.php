<?php

namespace App\Http\Controllers;

use App\Contact;
use App\CmsPage;
//use Illuminate\Http\Request;
use App\Http\Requests;
use App\ShopProduct;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Krucas\Notification\Facades\Notification;

class SiteController extends Controller
{
    public function getHome()
    {
         $verse_day = 'Do not judge, and you will not be judged.<br>
               Do not condemn, and you will not be condemned.<br>
                              Forgive, and you will be forgiven.<br>
                                              <br>
                 <span class="ital">LUKE 6:37</span>';

        $products = ShopProduct::whereIn('homepage_position',[1,2,3])->orderBy('homepage_position', 'ASC')->get();

        $homedata = [];
        $homedata['home_main_block'] = CmsPage::getPage('home_main_block');
        $homedata['home_reader_block'] = CmsPage::getPage('home_reader_block');
        $homedata['home_journey_block'] = CmsPage::getPage('home_journey_block');
        $homedata['home_community_block'] = CmsPage::getPage('home_community_block');
        $homedata['home_explore_block'] = CmsPage::getPage('home_explore_block');

        return view('site.home', ['verse_day' => $verse_day, 'products' => $products, 'homedata'=>$homedata]);
    }

    public function getAbout()
    {
        $page = CmsPage::getPage('about');
        return view('site.about', ['page'=>$page]);
    }

    public function getRecommendedResources()
    {
        $page = CmsPage::getPage('recommended_resources');
        return view('site.page', ['page'=>$page]);
    }

    public function getPartners()
    {
        $page = CmsPage::getPage('partners');
        return view('site.page', ['page'=>$page]);
    }

    public function getEvents()
    {
        $page = CmsPage::getPage('bsc_events');
        return view('site.events', ['page'=>$page]);
    }

    public function getHowItWorks()
    {
        $page = CmsPage::getPage('how_it_works');
        return view('site.how_it_works', ['page'=>$page]);
    }


    public function getSeminars()
    {
        $page = CmsPage::getPage('seminars');
        return view('site.seminars', ['page'=>$page]);
    }

    public function getMembership()
    {
        $page = CmsPage::getPage('membership');
        return view('site.membership', ['page'=>$page]);
    }

    public function anyContact(\Illuminate\Http\Request $request)
    {
        $model = new Contact();

        $page = CmsPage::getPage('contact_main');
        $page_aside = CmsPage::getPage('contact_aside');

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
            else{
                Notification::errorInstant('Message has not been sent');
            }
        }
        return view('site.contact', ['model' => $model, 'page' => $page, 'page_aside' => $page_aside]);
    }

    public function getFaq()
    {
        return view('site.faq', []);
    }
}
