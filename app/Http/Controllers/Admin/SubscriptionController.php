<?php

namespace App\Http\Controllers\Admin;

use App\Http\Components\MailchimpComponent;
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Input;


class SubscriptionController extends Controller
{
    public function getTest() {
         $e = MailchimpComponent::addEmailToList("powerrr@mail.ru");
        return $e;
    }
    private function prepareFilters($model)
    {
        $subscribedFilter = Input::get('subscription', false);

        if(!empty($subscribedFilter)){
            if($subscribedFilter==1) {
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
        $content['users'] = $userModel->orderBy('created_at', SORT_DESC)->paginate(20);
        return view('admin.subscription.list',
            [
                'page_title' => 'Subscription List',
                'content' => $content,
                'filterAction' => 'subscription/list/',
            ]);
    }

    public function getUpdateSubscribed()
    {
        $user_id = Request::input('id');
        $userModel = User::find($user_id);
        $userModel->update(Request::input());
        return $user_id;
    }
}
