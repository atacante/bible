<?php

namespace App\Console\Commands;

use App\BaseModel;
use App\Coupon;
use App\Helpers\ModelHelper;
use App\LexiconsListEn;
use App\VersesKingJamesEn;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckCouponExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupon:checkExpiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make all expired coupons status inactive';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $coupons = Coupon::where('status', true)->whereNotNull('expire_at')->where('expire_at', '<=', Carbon::now())->get();
        if ($coupons->count()) {
            foreach ($coupons as $coupon) {
                $coupon->status = false;
                $coupon->save();
            }
        }
    }
}
