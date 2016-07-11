<?php

/*
|--------------------------------------------------------------------------
| Cashier for Authorize.net
|--------------------------------------------------------------------------
|
| Define your subscriptions and plans here
|
*/
use \App\User;

return [

    // main
   User::PLAN_PREMIUM => [
        'name' => 'default',
        'interval' => [
            'length' => User::PLAN_PREMIUM_PERIOD, // number of instances for billing
            'unit' => 'days' //months, days, years
        ],
        'total_occurances' => 9999, // 9999 means without end date
        'trial_occurances' => 0,
        'amount' => User::PLAN_PREMIUM_COST,
        'trial_amount' => 0,
        'trial_days' => 0,
    ]

];
