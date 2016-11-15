<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('billing_first_name', 255)->nullable();
            $table->string('billing_last_name', 255)->nullable();
            $table->string('billing_address', 255)->nullable();
            $table->string('billing_city', 255)->nullable();
            $table->string('billing_postcode', 255)->nullable();
            $table->string('billing_country', 255)->nullable();
            $table->string('billing_state', 255)->nullable();
            $table->string('billing_email', 255)->nullable();
            $table->string('billing_phone', 255)->nullable();

            $table->string('shipping_first_name', 255)->nullable();
            $table->string('shipping_last_name', 255)->nullable();
            $table->string('shipping_address', 255)->nullable();
            $table->string('shipping_city', 255)->nullable();
            $table->string('shipping_postcode', 255)->nullable();
            $table->string('shipping_country', 255)->nullable();
            $table->string('shipping_state', 255)->nullable();
            $table->string('shipping_email', 255)->nullable();
            $table->string('shipping_phone', 255)->nullable();

            $table->timestamps();
            $table->foreign('user_id', 'user_meta_fk')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users_meta');
    }
}
