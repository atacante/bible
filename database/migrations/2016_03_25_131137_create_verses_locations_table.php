<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVersesLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('location_verse', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('verse_id');
            $table->integer('location_id');
            $table->integer('book_id')->nullable();
            $table->integer('chapter_num')->nullable();
            $table->integer('verse_num')->nullable();
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
        Schema::drop('location_verse');
    }
}
