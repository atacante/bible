<?php

namespace App\Http\Controllers;

use App\OrderItem;
use App\UsersMeta;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Krucas\Notification\Facades\Notification;

class OrderController extends Controller
{

    public function postCheckout(Request $request)
    {

        //Retrieve cart information
        $total = Cart::total();

        $charged = Auth::user()->charge($total, ['description' => 'online payment']);

        if(is_array($charged) && $charged['transId']){
            $userMeta = new UsersMeta();

            $this->validate($request, $userMeta->rules());
            $data = Input::all();

            if ($userMeta = $userMeta->create($data)) {
                $order = new Order();
                $order->total_paid = $total;
                $order->user_id= Auth::user()->id;
                $order->user_meta_id= $userMeta->id;
                $order->transaction_id= $charged['transId'];
                $order->save();

                foreach(Cart::content() as $item){
                    $orderItem = new OrderItem();
                    $orderItem->order_id=$order->id;
                    $orderItem->product_id=$item->model->id;
                    $orderItem->qty=$item->qty;
                    $orderItem->save();
                }

                Cart::destroy();
            }
            Notification::success('Your order was placed!');
            return redirect('/order/show/'.$order->id);

        }else{
            Notification::error('Your order was not placed!');
            return redirect('/shop/cart');
        }

    }

    public function getShow($orderId){
        $order = Order::where(['id' => $orderId, 'user_id' => Auth::user()->id])->first();

        if(!$order){
            Notification::error('Your are not allowed to see this order!');
            return Redirect::to('/shop');
        }

        return view('order.view',['order'=>$order]);
    }

    public function getCreate(){

        $model = UsersMeta::where(['user_id' => Auth::user()->id])->orderBy('created_at', SORT_DESC)->first();

        if(!$model){
            $model = new UsersMeta();
        }

        return view('order.create',
            [
                'model' => $model,
                'user_id' => Auth::user()->id,
                'page_title' => 'Create New Order'
            ]);
    }
}


