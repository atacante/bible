<?php

namespace App\Providers\CustomAuthorizeNet\Console;

use App\User;

class UserUpdateService
{
    public function runUpdates()
    {
        $users = User::all();

        foreach ($users as $user) {
            if($user->isPremiumPaid()){
                $user->plan_type = User::PLAN_PREMIUM;
                echo 'stay';
            }else{
                if($user->upgrade_plan){
                    $user->upgradeToPremium($user->upgrade_plan);
                    echo 'Upgraded';
                }else{
                    $user->plan_type = User::PLAN_FREE;
                    echo 'Downgraded';
                }

            }

            $user->save();
        }
    }
}
