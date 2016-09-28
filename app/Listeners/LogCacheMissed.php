<?php

namespace App\Listeners;

use App\User;
use Illuminate\Cache\Events\CacheMissed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;

class LogCacheMissed
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
     * @param  CacheMissed  $event
     * @return void
     */
    public function handle(CacheMissed $event)
    {
        /*$user = User::find(23);
        $user->debug = 'missed';
        $user->save();
        if($event->key == 'user-is-online-' . Auth::user()->id){
            $user = User::find(Auth::user()->id);
            $user->is_online = false;
            $user->save();
        }*/
    }
}
