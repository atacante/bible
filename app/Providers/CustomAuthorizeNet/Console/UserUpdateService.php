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
            }else{
                $user->plan_type = User::PLAN_FREE;
            }

            $user->save();
        }
    }
}
