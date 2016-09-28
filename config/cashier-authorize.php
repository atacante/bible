<?php

/*
|--------------------------------------------------------------------------
| Cashier for Authorize.net
|--------------------------------------------------------------------------
|
| Define your subscriptions and plans here
|
*/

return [

   '1 month' => [
        'name' => 'default',
        'interval' => [
            'length' => 1, // number of instances for billing
            'unit' => 'months' //months, days, years
        ],
        'total_occurances' => 9999, // 9999 means without end date
        'trial_occurances' => 0,
        'amount' => 100,
        'trial_amount' => 0,
        'trial_days' => 0,
    ],

    '3 months' => [
        'name' => 'default',
        'interval' => [
            'length' => 3, // number of instances for billing
            'unit' => 'months' //months, days, years
        ],
        'total_occurances' => 9999, // 9999 means without end date
        'trial_occurances' => 0,
        'amount' => 200,
        'trial_amount' => 0,
        'trial_days' => 0,
    ],

    '6 months' => [
        'name' => 'default',
        'interval' => [
            'length' => 6, // number of instances for billing
            'unit' => 'months' //months, days, years
        ],
        'total_occurances' => 9999, // 9999 means without end date
        'trial_occurances' => 0,
        'amount' => 300,
        'trial_amount' => 0,
        'trial_days' => 0,
    ],

    '1 year' => [
        'name' => 'default',
        'interval' => [
            'length' => 12, // number of instances for billing
            'unit' => 'months' //months, days
        ],
        'total_occurances' => 9999, // 9999 means without end date
        'trial_occurances' => 0,
        'amount' => 500,
        'trial_amount' => 0,
        'trial_days' => 0,
    ],

];
