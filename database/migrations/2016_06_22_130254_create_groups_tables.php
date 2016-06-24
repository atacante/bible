<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::create('groups', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('owner_id');
                $table->integer('category_id')->nullable();
                $table->string('group_name');
                $table->string('group_desc');
                $table->string('group_email');
                $table->string('group_image')->nullable();
                $table->string('access_level');
                $table->timestamps();
            });
            Schema::create('groups_users', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('group_id');
                $table->integer('user_id');
                $table->timestamps();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::transaction(function () {
            Schema::drop('groups');
            Schema::drop('groups_users');
        });
    }
}
