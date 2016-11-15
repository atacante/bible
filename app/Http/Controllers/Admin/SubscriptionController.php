<?php

namespace App\Http\Controllers\Admin;

use App\Http\Components\MailchimpComponent;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;

class SubscriptionController extends Controller
{
    public function getAdd()
    {
         $e = MailchimpComponent::addEmailToList("pinchuk.maksim@gmail.com");
        return $e;
    }
    public function getDel()
    {
        $e = MailchimpComponent::removeEmailFromList("pinchuk.maksim@gmail.com");
        return $e;
    }
    private function prepareFilters($model)
    {
        $subscribedFilter = Input::get('subscription', false);

        if (!empty($subscribedFilter)) {
            if ($subscribedFilter==1) {
                $model->where('subscribed', true);
            } else {
                $model->where('subscribed', false);
            }
        }

        return $model;
    }

    public function getList()
    {
        Session::flash('backUrl', Request::fullUrl());
        $userModel =  User::query();
        $userModel = $this->prepareFilters($userModel);
        $content['users'] = $userModel->whereHas('roles', function ($q) {
                                $q->whereNotIn('slug', [Config::get('app.role.admin')]);
        })->orderBy('created_at', SORT_DESC)->paginate(20);
        return view(
            'admin.subscription.list',
            [
                'page_title' => 'Subscription List',
                'content' => $content,
                'filterAction' => 'subscription/list/',
            ]
        );
    }

    public function getUpdateSubscribed()
    {
        $user_id = Request::input('id');
        $is_subscribed = Request::input('subscribed');
        $userModel = User::find($user_id);
        if ($is_subscribed) {
            $e = MailchimpComponent::addEmailToList($userModel->email);
        } else {
            $e = MailchimpComponent::removeEmailFromList($userModel->email);
        }
        if ($e=='') {
            $userModel->update(Request::input());
        }
        return  ['e'=>$e];
    }
}
