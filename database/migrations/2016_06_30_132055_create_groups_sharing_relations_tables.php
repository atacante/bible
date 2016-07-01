<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsSharingRelationsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups_shares', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id');
            $table->integer('groups_shares_id');
            $table->string('groups_shares_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('groups_shares');
    }
}
