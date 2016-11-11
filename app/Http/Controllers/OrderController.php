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
use Usps\RatePackage;

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

        $usps_rate = $this->getUspsRateAjax($data['shipping_postcode']);

        if(!is_numeric($usps_rate) || !$this->isTaxable()){
            $usps_rate = 0.00;
        }

        //Retrieve cart information
        $subtotal = Cart::total(2,'.','');
        $tax = 0.00;

        $state = strtolower(trim($data['shipping_state']));

        if((($state == 'florida')||($state == 'fl')) && $this->isTaxable()){
            $tax = round(0.07 * $subtotal, 2);
        }

        $total = $subtotal + $tax + $usps_rate;

        /* Charge Part */
        // Guests and Users
        $card = ['number' => $data['card_number'],
                 'code' => $data['card_code'],
                 'expiration' => $data['card_expiration'],
                 'billing_first_name' => $data['billing_first_name'],
                 'billing_last_name' => $data['billing_last_name'],
                 'billing_city' => $data['billing_city'],
                 'billing_zip' => $data['billing_postcode'],
                 'billing_country' => $data['billing_country'],
                 'billing_state' => $data['billing_state'],
                 'billing_address' => $data['billing_address'],
        ];
        $charged = User::chargeCreditCard($total, $card);

        if(is_array($charged) && $charged['transId']){

            $this->validate($request, $userMeta->rules());

            if ($userMeta = $userMeta->create($data)) {
                $order = new Order();
                $order->tax = $tax;
                $order->shipping_rate = $usps_rate;
                $order->subtotal = $subtotal;
                $order->total_paid = $total;
                $order->user_id= $data['user_id'];
                $order->user_meta_id= $userMeta->id;
                $order->transaction_id= $charged['transId'];
                $order->save();

                foreach(Cart::content() as $item){
                    $orderItem = new OrderItem();
                    $orderItem->order_id=$order->id;
                    $orderItem->product_id=$item->model->id;
                    $orderItem->qty=$item->qty;

                    if(isset($item->options['color'])){
                        $orderItem->color=$item->options['color'];
                    }

                    if(isset($item->options['size'])){
                        $orderItem->size=$item->options['size'];
                    }

                    $orderItem->save();
                }

                Cart::destroy();
            }
            Notification::success('Your order was placed! $'.$total.' was charged from your credit card');

            $this->sendMails($order);

            return redirect('/order/show/'.$order->id);

        }else{
            Notification::error('Your order was not placed! Please add valid credit card');
            return redirect('order/create');
        }

    }

    public function getShow($orderId){
        $user_id = 0;

        if(Auth::check()){
            $user_id = Auth::user()->id;
        }

        $order = Order::where(['id' => $orderId, 'user_id' => $user_id])->first();

        if(!$order){
            Notification::error('Your are not allowed to see this order!');
            return Redirect::to('/shop');
        }

        return view('order.view',['order'=>$order]);
    }

    public function getCreate(){
        $model = false;
        $user_id = 0;

        if(Auth::check()){
            $user_id = Auth::user()->id;
            $model = UsersMeta::where(['user_id' => $user_id])->orderBy('created_at', SORT_DESC)->first();
        }

        if(!$model){
            $model = new UsersMeta();
        }

        return view('order.create',
            [
                'model' => $model,
                'user_id' => $user_id,
                'page_title' => 'Create New Order'
            ]);
    }

    public function getUspsRateAjax($zip){

        if(empty($zip)){
            return 'Please fill Shipping Postcode';
        }

        // Initiate and set the username provided from usps
        $rate = new \Usps\Rate(env('USPS_USERNAME'));

        $package = new RatePackage;
        $package->setService(RatePackage::SERVICE_PRIORITY);
        //$package->setFirstClassMailType(RatePackage::MAIL_TYPE_PARCEL);
        $package->setZipOrigination(33707);
        $package->setZipDestination($zip);
        $package->setPounds(0);
        $package->setOunces(10);
        $package->setContainer(RatePackage::CONTAINER_SM_FLAT_RATE_BOX);
        $package->setSize(RatePackage::SIZE_REGULAR);
        //  $package->setField('Machinable', true);

        // add the package to the rate stack
        $rate->addPackage($package);

        // Perform the request and print out the result
        $rate->getRate();

        $response =  $rate->getArrayResponse();

        if($rate->isSuccess()){
            $usps_rate = $response['RateV4Response']['Package']['Postage']['Rate'];
            return $usps_rate;
        }else{
            return $response['RateV4Response']['Package']['Error']['Description'];
        }
    }

    private function isTaxable(){
        foreach(Cart::content() as $item){
            if(!$item->model->taxable){
                return false;
            }
        }

        return true;
    }
}


