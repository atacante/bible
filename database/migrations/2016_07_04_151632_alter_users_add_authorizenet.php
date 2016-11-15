<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersAddAuthorizenet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->string('authorize_id')->nullable();
            $table->string('authorize_payment_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('authorize_id');
            $table->dropColumn('authorize_payment_id');
            $table->dropColumn('card_brand');
            $table->dropColumn('card_last_four');
        });
    }
}
