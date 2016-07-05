<?php

namespace App\Providers\CustomAuthorizeNet;

use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\constants as AnetConstants;
use net\authorize\api\controller as AnetController;
use Laravel\CashierAuthorizeNet\Billable as ABillable;
use Laravel\CashierAuthorizeNet\Requestor;

trait Billable
{
    use ABillable;

    /**
     * Begin creating a new subscription.
     *
     * @param  string  $subscription
     * @param  string  $plan
     * @param  float  $amount
     * @return SubscriptionBuilder
     */
    public function newSubscription($subscription, $plan, $amount = false)
    {
        if(!$this->hasAuthorizeId()){
            $this->createAsAuthorizeCustomer(['number' => '4007000000027', 'experation' => '2020-12']);
        }
        return new SubscriptionBuilder($this, $subscription, $plan, $amount);
    }

    /**
     * Create a Stripe customer for the given user.
     *
     * @param  array $creditCardDetails
     * @return StripeCustomer
     */
    public function createAsAuthorizeCustomer($creditCardDetails)
    {
        $creditCard = new AnetAPI\CreditCardType();
        $creditCard->setCardNumber($creditCardDetails['number']);
        $creditCard->setExpirationDate($creditCardDetails['experation']);
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

        $billto->setAddress($this->address);
        $billto->setCity($this->city);
        $billto->setState($this->state);
        $billto->setZip($this->zip);
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

        $controller = new AnetController\CreateCustomerProfileController($request);

        $response = $controller->executeWithApiResponse($requestor->env);

        if (($response != null) && ($response->getMessages()->getResultCode() === "Ok") ) {
            $this->authorize_id = $response->getCustomerProfileId();
            $this->authorize_payment_id = $response->getCustomerPaymentProfileIdList()[0];
            $this->card_brand = $this->cardBrandDetector($creditCardDetails['number']);
            $this->card_last_four = substr($creditCardDetails['number'], -4);
            $this->save();
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            Log::error("Authorize.net Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText());
        }

        return $this;
    }

}
