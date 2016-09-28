<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNasbLexiconTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lexicon_nasb', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('book_id');
            $table->integer('chapter_num');
            $table->integer('verse_num');
            $table->string('verse_part')->nullable();
            $table->string('strong_num')->nullable();
            $table->string('strong_1_word_def')->nullable();
            $table->string('transliteration')->nullable();
            $table->text('symbolism')->nullable();
            $table->text('definition')->nullable();
            $table->text('verse_part_el')->nullable();
            $table->text('verse_part_he')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lexicon_nasb');
    }
}
