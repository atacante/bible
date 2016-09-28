<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use App\ShopCategory;
use App\Helpers\ViewHelper;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Krucas\Notification\Facades\Notification;

class ShopOrdersController extends Controller
{

    public function getList()
    {
        $orderModel = Order::query();

        $content['orders'] = $orderModel->with('user')->orderBy('id')->paginate(20);
        return view('admin.shop.orders.list',
            [
                'page_title' => 'Orders',
                'content' => $content,
                'filterAction' => 'shop-orders/list/',
            ]);
    }

    public function getView($orderId){
        $order = Order::find($orderId);

        if(!$order){
            Notification::error('There no such order!');
            return Redirect::to('shop-orders/list');
        }

        return view('admin.shop.orders.view',['order'=>$order,'page_title' => 'Order Details',]);
    }

}
