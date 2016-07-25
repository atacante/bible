<?php

namespace App\Providers\CustomAuthorizeNet;

use Illuminate\Support\Facades\Config;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\constants as AnetConstants;
use net\authorize\api\controller as AnetController;
use Laravel\CashierAuthorizeNet\Billable as ABillable;
use Laravel\CashierAuthorizeNet\Requestor;
use Illuminate\Support\Facades\Input;
use Krucas\Notification\Facades\Notification;
use Illuminate\Support\Facades\Request;

trait Billable
{
    use ABillable;

    protected function processResult($result){
        $instant = '';

        if(Request::is('user/profile')){
            $instant = 'Instant';
        }

        foreach($result as $type){
            if($type['success']){
                $method = 'success'.$instant;
                Notification::$method($type['message']);
            }else{
                $method = 'error'.$instant;
                if(!empty($type['message'])){
                    Notification::$method($type['message']);
                }

            }
        }

        if(!$result){
            $result = [
                'subscription' =>  ['success' => false, 'message' => ''],
                'account' =>  ['success' => false, 'message' => '']
            ];
        }

        return $result;
    }

    public function createAccountAndOrSubscribe($plan = false, $amount = false){

        $result = [
            'subscription' =>  ['success' => false, 'message' => ''],
            'account' =>  ['success' => false, 'message' => '']
        ];

        if($card = $this->askToCreateAccount()){
            $result['account'] =  $this->createOrUpdatePaymentAccount($card);
        }

        if($this->askToCreateSubscription($plan)){
            if(!$this->hasPaymentAccount()){
                $result['subscription'] =  ['success' => false, 'message' => 'You must have Credit Card'];
            }else{
                // Pause is necessary on Authorize between create account and subscribe
                if($result['account']['success']){
                    sleep(10);
                }
                $this->cancelOldPlan();
                $result['subscription'] = $this->newSubscription($plan, $amount)->create();
            }

        }

        return $this->processResult($result);
    }

    /**
     * Begin creating a new subscription.
     *
     * @param  string  $plan
     * @param  float  $amount
     * @return SubscriptionBuilder
     */
    public function newSubscription($plan, $amount = false)
    {
        return new SubscriptionBuilder($this, $plan, $amount);
    }

    /**
     * Create a Stripe customer for the given user.
     *
     * @param  array $creditCardDetails
     * @return array
     */
    public function createAsAuthorizeCustomer($creditCardDetails)
    {
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($creditCardDetails['number']);
        $creditCard->setExpirationDate($creditCardDetails['expiration']);
        $paymentCreditCard = new AnetAPI\PaymentType();
        $paymentCreditCard->setCreditCard($creditCard);

        $name = explode(' ', $this->name);

        $billto = new AnetAPI\CustomerAddressType();
        $billto->setFirstName($name[0]);
        if(isset($name[1])){
            $billto->setLastName($name[1]);
        }else{
            $billto->setLastName($name[0]);
        }

        if($this->address){
            $billto->setAddress($this->address);
        }else{
            $billto->setAddress('Default');
        }
        $billto->setCity($this->city);
        $billto->setState($this->state);

        if($this->zip){
            $billto->setZip($this->zip);
        }else{
            $billto->setZip(11111);
        }

        $billto->setCountry($this->country);

        $paymentprofile = new AnetAPI\CustomerPaymentProfileType();
        $paymentprofile->setCustomerType('individual');
        $paymentprofile->setBillTo($billto);
        $paymentprofile->setPayment($paymentCreditCard);

        $customerprofile = new AnetAPI\CustomerProfileType();
        $customerprofile->setMerchantCustomerId("M_".$this->id);
        $customerprofile->setEmail($this->email);
        $customerprofile->setPaymentProfiles([$paymentprofile]);

        $requestor = new Requestor();
        $request = $requestor->prepare(new AnetAPI\CreateCustomerProfileRequest());
        $request->setProfile($customerprofile);
        $request->setValidationMode(getenv('ADN_MODE'));

        $controller = new AnetController\CreateCustomerProfileController($request);

        $response = $controller->executeWithApiResponse($requestor->env);

        if (($response != null) && ($response->getMessages()->getResultCode() === "Ok") ) {
            $this->authorize_id = $response->getCustomerProfileId();
            $this->authorize_payment_id = $response->getCustomerPaymentProfileIdList()[0];
            $this->card_brand = $this->cardBrandDetector($creditCardDetails['number']);
            $this->card_last_four = substr($creditCardDetails['number'], -4);
            $this->save();

            $result = [
                'success' => true,
                'message' => 'Credit Card was added'
            ];
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            //Log::error("Authorize.net Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText());

            $result = [
                'success' => false,
                'message' => 'Credit Card was not updated. Please provide valid Credit Card'
            ];
        }

        return $result;
    }

    /**
     * Update customer's credit card.
     *
     * @param  array  $card
     * @return void
     */
    public function updateCard($card)
    {
        $requestor = new Requestor();
        $request = $requestor->prepare(new AnetAPI\UpdateCustomerPaymentProfileRequest());
        $request->setCustomerProfileId($this->authorize_id);
        $controller = new AnetController\GetCustomerProfileController($request);

        // We're updating the billing address but everything has to be passed in an update
        // For card information you can pass exactly what comes back from an GetCustomerPaymentProfile
        // if you don't need to update that info
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($card['number']);
        $creditCard->setExpirationDate($card['expiration']);
        $paymentCreditCard = new AnetAPI\PaymentType();
        $paymentCreditCard->setCreditCard($creditCard);

        // Create the Bill To info for new payment type
        $name = explode(' ', $this->name);
        $billto = new AnetAPI\CustomerAddressType();
        $billto->setFirstName($name[0]);
        if(isset($name[1])){
            $billto->setLastName($name[1]);
        }else{
            $billto->setLastName($name[0]);
        }
        if($this->address){
            $billto->setAddress($this->address);
        }else{
            $billto->setAddress('Default');
        }

        $billto->setCity($this->city);
        $billto->setState($this->state);

        if($this->zip){
            $billto->setZip($this->zip);
        }else{
            $billto->setZip(11111);
        }

        $billto->setCountry($this->country);

        // Create the Customer Payment Profile object
        $paymentprofile = new AnetAPI\CustomerPaymentProfileExType();
        $paymentprofile->setCustomerPaymentProfileId($this->authorize_payment_id);
        $paymentprofile->setBillTo($billto);
        $paymentprofile->setPayment($paymentCreditCard);

        // Submit a UpdatePaymentProfileRequest
        $request->setPaymentProfile($paymentprofile);
        $request->setValidationMode(getenv('ADN_MODE'));

        $controller = new AnetController\UpdateCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse($requestor->env);
        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok")) {
            $this->card_brand = $this->cardBrandDetector($card['number']);
            $this->card_last_four = substr($card['number'], -4);
            $this->save();

            $result = [
                'success' => true,
                'message' => 'Credit Card was updated'
            ];
        } else {
            $result = [
                'success' => false,
                'message' => 'Credit Card was not updated. Please provide valid Credit Card'
            ];
        }

        return $result;
    }

    /**
     * Create or Update payment account.
     *
     * @param  array  $card = ['number' => '1111111111111111', 'expiration' => '2018-05']
     * @return array
     */
    public function createOrUpdatePaymentAccount($card){
        if(!$this->hasPaymentAccount()){
            $result = $this->createAsAuthorizeCustomer($card);
        }else{
            $result = $this->updateCard($card);
        }

        return $result;
    }

    /**
     * Determine if the entity has an Authorize payment ID.
     *
     * @return bool
     */
    public function hasAuthorizePaymentId(){
        return ! is_null($this->authorize_payment_id);
    }

    /**
     * Determine if the entity has an Authorize payment account
     *
     * @return bool
     */
    public function hasPaymentAccount(){
        return $this->hasAuthorizeId() && $this->hasAuthorizePaymentId();
    }

    /**
     * Determine if the entity asks to create an Authorize payment account
     *
     * @return bool
     */
    public function askToCreateAccount(){
        if(($card['number'] = Input::get('card_number')) && ($card['expiration'] = Input::get('card_expiration'))){
            return $card;
        }

        return false;
    }

    /**
     * Determine if the entity asks to create an Authorize subscription
     *
     * @return bool
     */
    public function askToCreateSubscription($plan){
        return !$this->onPlan($plan);
    }

    public static function getPremiumCost($plan = '1 month'){
        return Config::get('cashier-authorize')[$plan]['amount'];
    }

    public static function getPossiblePlans(){
        return Config::get('cashier-authorize');
    }

    public function cancelOldPlan(){
        if($this->subscription()) {
            $this->subscription()->cancelNow();
        }
    }

    /**
     * Get a subscription instance by name.
     *
     * @param  string  $subscription
     * @return \Laravel\Cashier\Subscription|null
     */
    public function subscription($subscription = 'default')
    {
        return $this->subscriptions()->get()->sortByDesc(function ($value) {
            return $value->created_at->getTimestamp();
        })
            ->first(function ($key, $value) use ($subscription) {
                return $value->name === $subscription;
            });
    }
}
