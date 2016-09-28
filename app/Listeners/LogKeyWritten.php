<?php

namespace App\Listeners;

use App\User;
use Illuminate\Cache\Events\KeyWritten;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LogKeyWritten
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
     * @param  KeyWritten  $event
     * @return void
     */
    public function handle(KeyWritten $event)
    {
//        $user = User::find(23);
//        $user->debug = 'written';
//        $user->save();
//        if($event->key == 'user-is-online-' . Auth::user()->id){
//            $user = User::find(Auth::user()->id);
//            $user->is_online = true;
//            $user->save();
//        }
    }
}
