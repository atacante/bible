<?php

namespace App\Console;

use App\BlogCategory;
use App\WallPost;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
        Commands\ParseCsvCommand::class,
        Commands\CacheLexicon::class,
        Commands\CacheSymbolism::class,
        Commands\CheckCouponExpiration::class,
        \App\Providers\CustomAuthorizeNet\Console\SubscriptionUpdates::class,

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')->hourly();
        $schedule->command('subscription:update')->hourly();
        $schedule->command('coupon:checkExpiration')->daily();
    }
}
