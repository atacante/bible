<?php

namespace App\Providers\CustomAuthorizeNet;

use App\UsersMeta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Laravel\CashierAuthorizeNet\Subscription;
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

        if(isset($result['subscription']['remove'])){
            $result['subscription'] = ['success' => false, 'message' => ''];
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
            if(!$this->hasPaymentAccount() || $amount <= 0){
                if($this->newSubscription($plan, $amount)->createTrial()){
                    if(Auth::check() && Auth::user()->is(Config::get('app.role.admin'))){
                        $user = 'User '.$this->name;
                    }
                    else{
                        $user = 'Your';
                    }
                    $result['subscription'] = ['success' => true, 'message' => $user.' have a trial premium account - ends at '.date_format($this->subscription()->ends_at, 'Y-m-d')];
                }else{
                    $result['subscription'] =  ['success' => false, 'message' => 'You must have Credit Card'];
                }
            }else{
                // Pause is necessary on Authorize between create account and subscribe
                if($result['account']['success']){
                    sleep(10);
                }
                if($this->cancelOldPlan($plan)){
                    $result['subscription'] = $this->newSubscription($plan, $amount)->create();
                }else{
                    // Plan to change subscription at the end
                    $result['subscription'] = ['remove' => true ,'success' => true, 'message' => 'Your will be moved to new subscription plan at '.date_format($this->subscription()->ends_at, 'Y-m-d')];
                }
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
        $paymentCreditCard = self::setPaymentCreditCard($creditCardDetails);

        $billto = $this->setBillTo($creditCardDetails);

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

            $userMeta = new UsersMeta();

            $userMeta->billing_first_name = $billto->getFirstName();
            $userMeta->billing_last_name = $billto->getLastName();
            $userMeta->billing_address = $billto->getAddress();
            $userMeta->billing_postcode = $billto->getZip();

            $this->userMeta()->save($userMeta);

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

        $paymentCreditCard = self::setPaymentCreditCard($card);

        $billto = $this->setBillTo($card);

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

            $this->userMeta->billing_first_name = $billto->getFirstName();
            $this->userMeta->billing_last_name = $billto->getLastName();
            $this->userMeta->billing_address = $billto->getAddress();
            $this->userMeta->billing_postcode = $billto->getZip();

            $this->userMeta->save();

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
        if( ($card['number'] = Input::get('card_number')) &&
            ($card['expiration'] = Input::get('card_expiration'))
        ){

            $card['code'] = Input::get('card_code');
            $card['billing_name'] = Input::get('billing_name');
            $card['billing_address'] = Input::get('billing_address');
            $card['billing_zip'] = Input::get('billing_zip');

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
        return  ($plan && !$this->onPlan($plan));
    }

    public static function getPremiumCost($plan = '1 month'){
        return Config::get('cashier-authorize')[$plan]['amount'];
    }

    public static function getPossiblePlans(){
        return Config::get('cashier-authorize');
    }

    public function cancelOldPlan($new_plan){

        $canceledNow = true;

        if($this->subscription()) {
            if($this->upgrade_plan){
                if(!$this->isOnCoupon()){
                    $this->subscription()->cancelNow();
                }
            }else{
                if(!$this->isOnCoupon()){
                    $this->subscription()->cancel();
                }
                $this->upgrade_plan = $new_plan;
                $this->save();
                $canceledNow = false;
            }

        }

        return $canceledNow;
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

    public static function chargeCreditCard($amount, $card){

        // Create the payment data for a credit card
        $paymentOne = self::setPaymentCreditCard($card);
        $billto = self::setBillToForUnauthorized($card);

        //create a transaction
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType("authCaptureTransaction");
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setPayment($paymentOne);
        $transactionRequestType->setBillTo($billto);

        $requestor = new Requestor();
        $request = $requestor->prepare((new AnetAPI\CreateTransactionRequest()));
        $request->setTransactionRequest($transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse($requestor->env);

        if ($response != null) {
            $tresponse = $response->getTransactionResponse();
            if (($tresponse != null) && ($tresponse->getResponseCode() == '1') ) {
                return [
                    'authCode' => $tresponse->getAuthCode(),
                    'transId' => $tresponse->getTransId(),
                ];
            } else if (($tresponse != null) && ($tresponse->getResponseCode() == "2") ) {
                return false;
            } else if (($tresponse != null) && ($tresponse->getResponseCode() == "4") ) {
                throw new Exception("ERROR: HELD FOR REVIEW", 1);
            }
        } else {
            throw new Exception("ERROR: NO RESPONSE", 1);
        }

        return false;
    }

    public function isOnCoupon(){
        $subscription = $this->subscription();

        if(!$subscription){
            return false;
        }

        if(($this->subscription()->authorize_id == '111111111111') &&
           ($this->subscription()->authorize_payment_id == '111111111111')
          )
        {
            return true;
        }

        return false;
    }

    protected static function setPaymentCreditCard($card){
        // We're updating the billing address but everything has to be passed in an update
        // For card information you can pass exactly what comes back from an GetCustomerPaymentProfile
        // if you don't need to update that info
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($card['number']);
        $creditCard->setExpirationDate($card['expiration']);

        if($card['code']){
            $creditCard->setCardCode($card['code']);
        }

        $paymentCreditCard = new AnetAPI\PaymentType();
        $paymentCreditCard->setCreditCard($creditCard);

        return $paymentCreditCard;
    }

    protected function setBillTo($card){
        if(isset($card['billing_name'])){
            $name = explode(' ', $card['billing_name']);
        }else{
            $name = explode(' ', $this->name);
        }

        $billto = new AnetAPI\CustomerAddressType();
        $billto->setFirstName($name[0]);
        if(isset($name[1])){
            $billto->setLastName($name[1]);
        }else{
            $billto->setLastName($name[0]);
        }

        if(isset($card['billing_address'])){
            $billto->setAddress($card['billing_address']);
        }elseif($this->address){
            $billto->setAddress($this->address);
        }else{
            $billto->setAddress('Default');
        }

        $billto->setCity($this->city);
        $billto->setState($this->state);

        if(isset($card['billing_zip'])){
            $billto->setZip($card['billing_zip']);
        }elseif($this->zip){
            $billto->setZip($this->zip);
        }else{
            $billto->setZip(11111);
        }

        if($this->country){
            $billto->setCountry($this->country->nicename);
        }

        return $billto;

    }

    // ToDo Need to Refactor
    protected static function setBillToForUnauthorized($card){

        $billto = new AnetAPI\CustomerAddressType();

        if(isset($card['billing_first_name'])){
            $billto->setFirstName($card['billing_first_name']);
        }else{
            $billto->setFirstName('');
        }

        if(isset($card['billing_last_name'])){
            $billto->setLastName($card['billing_last_name']);
        }else{
            $billto->setLastName('');
        }

        if(isset($card['billing_address'])){
            $billto->setAddress($card['billing_address']);
        }else{
            $billto->setAddress('Default');
        }

        if(isset($card['billing_city'])){
            $billto->setCity($card['billing_city']);
        }else{
            $billto->setCity('Default');
        }


        if(isset($card['billing_state'])){
            $billto->setState($card['billing_state']);
        }else{
            $billto->setState('Default');
        }

        if(isset($card['billing_zip'])){
            $billto->setZip($card['billing_zip']);
        }else{
            $billto->setZip(11111);
        }

        if(isset($card['billing_country'])){
            $billto->setCountry($card['billing_country']);
        }else{
            $billto->setCountry('USA');
        }

        return $billto;

    }
}
