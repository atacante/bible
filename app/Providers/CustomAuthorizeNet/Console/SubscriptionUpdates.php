<?php

namespace App\Providers\CustomAuthorizeNet\Console;

use Illuminate\Console\Command;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

class SubscriptionUpdates extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'subscription:updates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Your Authorize.net subscriptions will be updated via a check with their status on Authorize.net';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $subscriptionUpdateService = new SubscriptionUpdateService();
        $subscriptionUpdateService->runUpdates();

        $userUpdateService = new UserUpdateService();
        $userUpdateService->runUpdates();
    }
}
