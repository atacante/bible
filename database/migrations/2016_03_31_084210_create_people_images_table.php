<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('people_images', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('people_id');
            $table->string('image',255);
            $table->timestamps();
            $table->foreign('people_id', 'people_images_fk')->references('id')->on('peoples')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('people_images');
    }
}
