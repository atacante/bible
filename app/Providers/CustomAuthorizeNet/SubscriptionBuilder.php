<?php

namespace App\Providers\CustomAuthorizeNet;

use DateTime;
use Exception;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Laravel\CashierAuthorizeNet\Requestor;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\constants as AnetConstants;
use net\authorize\api\controller as AnetController;

use Laravel\CashierAuthorizeNet\SubscriptionBuilder as ASubscriptionBuilder;

class SubscriptionBuilder extends ASubscriptionBuilder
{
    public $amount;

    /**
     * Create a new subscription builder instance.
     *
     * @param  mixed  $user
     * @param  string  $name
     * @param  string  $plan
     * @return void
     */
    public function __construct($user, $name, $plan, $amount = false)
    {
        $this->user = $user;
        $this->name = $name;
        $this->plan = $plan;
        if(!$amount){
            $amount = round(floatval(Config::get('cashier-authorize')[$this->plan]['amount']) * floatval('1.'.$this->getTaxPercentageForPayload()), 2);
        }
        $this->amount = round($amount,2);
        $this->requestor = new Requestor;
    }

    /**
     * Create a new Authorize subscription.
     *
     * @param  string|null  $token
     * @param  array  $options
     * @return \Laravel\Cashier\Subscription
     */
    public function create()
    {
        $config = Config::get('cashier-authorize');

        // Subscription Type Info
        $subscription = new AnetAPI\ARBSubscriptionType();
        $subscription->setName($config[$this->plan]['name']);

        $interval = new AnetAPI\PaymentScheduleType\IntervalAType();
        $interval->setLength($config[$this->plan]['interval']['length']);
        $interval->setUnit($config[$this->plan]['interval']['unit']);

        $trialDays = $config[$this->plan]['trial_days'];
        $this->trialDays($trialDays);

        // Must use mountain time according to Authorize.net
        $nowInMountainTz = Carbon::now('America/Denver')->addDays($trialDays);

        $paymentSchedule = new AnetAPI\PaymentScheduleType();
        $paymentSchedule->setInterval($interval);
        $paymentSchedule->setStartDate(new DateTime($nowInMountainTz));
        $paymentSchedule->setTotalOccurrences($config[$this->plan]['total_occurances']);
        $paymentSchedule->setTrialOccurrences($config[$this->plan]['trial_occurances']);

        $amount = $this->amount;

        $subscription->setPaymentSchedule($paymentSchedule);
        $subscription->setAmount($amount);
        $subscription->setTrialAmount($config[$this->plan]['trial_amount']);

        $profile = new AnetAPI\CustomerProfileIdType();
        $profile->setCustomerProfileId($this->user->authorize_id);
        $profile->setCustomerPaymentProfileId($this->user->authorize_payment_id);
        $subscription->setProfile($profile);

        $requestor = new Requestor();
        $request = $requestor->prepare((new AnetAPI\ARBCreateSubscriptionRequest()));
        $request->setSubscription($subscription);
        $controller = new AnetController\ARBCreateSubscriptionController($request);

        $response = $controller->executeWithApiResponse($requestor->env);

        if (($response != null) && ($response->getMessages()->getResultCode() == "Ok") ) {
            if ($this->skipTrial) {
                $trialEndsAt = null;
            } else {
                $trialEndsAt = $this->trialDays ? Carbon::now()->addDays($this->trialDays) : null;
            }

            return $this->user->subscriptions()->create([
                'name' => $this->name,
                'authorize_id' => $response->getSubscriptionId(),
                'authorize_plan' => $this->plan,
                'authorize_payment_id' => $this->user->authorize_payment_id,
                'metadata' => json_encode([
                    'refId' => $requestor->refId
                ]),
                'quantity' => $this->quantity,
                'trial_ends_at' => $trialEndsAt,
                'ends_at' => null,
            ]);
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            throw new Exception("Response : " . $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText(), 1);
        }
    }

}
