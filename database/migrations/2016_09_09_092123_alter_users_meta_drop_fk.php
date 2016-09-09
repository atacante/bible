<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersMetaDropFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_meta', function(Blueprint $table)
        {
            $table->dropForeign('user_meta_fk');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_meta', function(Blueprint $table)
        {
            $table->foreign('user_id', 'user_meta_fk')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });


    }
}
