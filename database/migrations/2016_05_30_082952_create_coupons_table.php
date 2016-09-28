<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->string('member_type')->nullable();
            $table->string('coupon_type')->nullable();
            $table->boolean('status')->default(true);
            $table->decimal('amount',11,2);
            $table->string('coupon_code')->unique();
            $table->integer('uses_limit')->nullable()->default(0);
            $table->integer('used')->nullable();
            $table->timestamp('expire_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('coupons');
    }
}
