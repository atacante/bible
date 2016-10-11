<?php

namespace App\Http\Controllers;

use App\OrderItem;
use App\User;
use App\UsersMeta;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Krucas\Notification\Facades\Notification;

class OrderController extends Controller
{

    private function sendMails($order){
        $userEmail = $order->userMeta->shipping_email;

        if(empty($userEmail)){
            $userEmail = $order->user->email;
        }

        $adminEmails = User::whereHas('roles', function ($q) {
            $q->whereIn('slug',[Config::get('app.role.admin')]);
        })->pluck('email')->toArray();

        $data['order'] = $order;

        array_push($adminEmails, $userEmail);
        $data['emails'] = $adminEmails;

        foreach($data['emails'] as $email){
            Mail::send('emails.order', $data, function($message) use($data, $email)
            {
                $message->to($email)->subject('Order Confirmation');
            });
        }

        return true;
    }

    public function postCheckout(Request $request)
    {

        $userMeta = new UsersMeta();
        $this->validate($request, $userMeta->rules());

        $data = $request->all();

        //Retrieve cart information
        $subtotal = Cart::total();
        $tax = 0.00;

        if(strtolower(trim($data['shipping_state'])) == 'florida'){
            $tax = round(0.07 * $subtotal, 2);
        }

        $total = $subtotal + $tax;

        $charged = Auth::user()->charge($total, ['description' => 'online payment']);

        if(is_array($charged) && $charged['transId']){

            $this->validate($request, $userMeta->rules());

            if ($userMeta = $userMeta->create($data)) {
                $order = new Order();
                $order->tax = $tax;
                $order->subtotal = $subtotal;
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
            Notification::success('Your order was placed! $'.$total.' was charged from your credit card');

            $this->sendMails($order);

            return redirect('/order/show/'.$order->id);

        }else{
            Notification::error('Your order was not placed! Please add valid credit card');
            return redirect('/user/profile');
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

        if(!Auth::user()->hasPaymentAccount()){
            Notification::error('Please add valid credit card to finish your order!');
           return redirect()->guest('user/profile');
        }

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


