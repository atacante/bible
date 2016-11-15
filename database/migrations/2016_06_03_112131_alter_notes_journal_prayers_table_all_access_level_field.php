<?php

use App\Journal;
use App\Note;
use App\Prayer;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNotesJournalPrayersTableAllAccessLevelField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::table('notes', function ($table) {
                $table->string('access_level')->default(Note::ACCESS_PRIVATE);
            });
            Schema::table('journal', function ($table) {
                $table->string('access_level')->default(Journal::ACCESS_PRIVATE);
            });
            Schema::table('prayers', function ($table) {
                $table->string('access_level')->default(Prayer::ACCESS_PRIVATE);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::transaction(function () {
            Schema::table('notes', function ($table) {
                $table->dropColumn('access_level');
            });
            Schema::table('journal', function ($table) {
                $table->dropColumn('access_level');
            });
            Schema::table('prayers', function ($table) {
                $table->dropColumn('access_level');
            });
        });
    }
}
