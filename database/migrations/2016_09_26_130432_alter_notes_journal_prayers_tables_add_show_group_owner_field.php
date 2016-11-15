<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNotesJournalPrayersTablesAddShowGroupOwnerField extends Migration
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
                $table->boolean('only_show_group_owner')->default(false);
            });
            Schema::table('journal', function ($table) {
                $table->boolean('only_show_group_owner')->default(false);
            });
            Schema::table('prayers', function ($table) {
                $table->boolean('only_show_group_owner')->default(false);
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
                $table->dropColumn('only_show_group_owner');
            });
            Schema::table('journal', function ($table) {
                $table->dropColumn('only_show_group_owner');
            });
            Schema::table('prayers', function ($table) {
                $table->dropColumn('only_show_group_owner');
            });
        });
    }
}
