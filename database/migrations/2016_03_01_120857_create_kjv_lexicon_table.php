<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKjvLexiconTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lexicon_kjv', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('book_id');
            $table->integer('chapter_num');
            $table->integer('verse_num');
            $table->string('verse_part');
            $table->string('strong_num');
            $table->string('strong_1_word_def');
            $table->string('transliteration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lexicon_kjv');
    }
}
