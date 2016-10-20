<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRomazyanTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('romazyan_test', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 80);
            $table->string('last_name', 80);
            $table->boolean('security')->default(true);
            $table->boolean('checked_automated_servers_updating')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('romazyan_test');
    }
}
