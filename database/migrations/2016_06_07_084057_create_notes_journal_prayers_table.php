<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesJournalPrayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes_journal_prayers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('note_id')->nullable();
            $table->integer('journal_id')->nullable();
            $table->integer('prayer_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notes_journal_prayers');
    }
}
