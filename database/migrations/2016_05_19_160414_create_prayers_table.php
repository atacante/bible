<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prayers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('note_id')->nullable();
            $table->integer('journal_id')->nullable();
            $table->string('bible_version')->nullable();
            $table->integer('verse_id')->nullable();
            $table->integer('lexicon_id')->nullable();
            $table->text('highlighted_text')->nullable();
            $table->text('prayer_text');
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
        Schema::drop('prayers');
    }
}
