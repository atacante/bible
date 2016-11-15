<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNotesJournalPrayersTablesAddRelationsCodeField extends Migration
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
                $table->string('rel_code')->nullable();
            });
            Schema::table('journal', function ($table) {
                $table->string('rel_code')->nullable();
            });
            Schema::table('prayers', function ($table) {
                $table->string('rel_code')->nullable();
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
                $table->dropColumn('rel_code');
            });
            Schema::table('journal', function ($table) {
                $table->dropColumn('rel_code');
            });
            Schema::table('prayers', function ($table) {
                $table->dropColumn('rel_code');
            });
        });
    }
}
