<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::create('us_states', function (Blueprint $table) {
                $table->increments('id');
                $table->string('code', 2);
                $table->string('name', 64);
            });
            DB::unprepared(File::get(base_path('resources/data/us_states.sql')));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('us_states');
    }
}
