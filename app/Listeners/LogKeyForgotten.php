<?php

namespace App\Listeners;

use App\User;
use Illuminate\Cache\Events\KeyForgotten;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class LogKeyForgotten
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  KeyForgotten  $event
     * @return void
     */
    public function handle(KeyForgotten $event)
    {
//        $user = User::find(23);
//        $user->debug = 'forgotten';
//        $user->save();
//        if($event->key == 'user-is-online-' . Auth::user()->id){
//            $user = User::find(Auth::user()->id);
//            $user->is_online = false;
//            $user->save();
//        }
    }
}
