<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVersionsListEngTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('versions_list_en', function (Blueprint $table) {
            $table->increments('id');
            $table->string('version_name')->unique('versions_list_en_version_name_key');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('versions_list_en');
    }
}
