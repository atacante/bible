<?php

namespace App\Listeners;

use App\User;
use Illuminate\Cache\Events\CacheHit;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogCacheHit
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
     * @param  CacheHit  $event
     * @return void
     */
    public function handle(CacheHit $event)
    {
        /*$user = User::find(23);
        $user->debug = 'hit';
        $user->save();*/
    }
}
