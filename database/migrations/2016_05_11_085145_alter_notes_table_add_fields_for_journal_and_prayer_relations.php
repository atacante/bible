<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNotesTableAddFieldsForJournalAndPrayerRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notes', function ($table) {
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
        Schema::table('notes', function ($table) {
            $table->dropColumn('journal_id');
            $table->dropColumn('prayer_id');
        });
    }
}
