<?php

namespace App\Http\Controllers\Admin;

/*use App\ShopProduct;
use App\ShopCategory;*/
use App\User;
use App\Http\Requests;
use App\Http\Controllers\Controller;


class SubscriptionController extends Controller
{
    public function getList()
    {
        $content['users'] = User::orderBy('created_at', SORT_DESC)->paginate(20);

        return view('admin.subscription.list',
            [
                'page_title' => 'Subscription List',
                'content' => $content,
                'filterAction' => 'subscription/list/',
            ]);
    }
}
