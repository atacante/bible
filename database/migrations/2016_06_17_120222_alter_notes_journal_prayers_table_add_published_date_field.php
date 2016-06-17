<?php

use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNotesJournalPrayersTableAddPublishedDateField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::table('notes', function($table)
            {
                $table->string('published_at')->default(Carbon::now())->nullable();
            });
            Schema::table('journal', function($table)
            {
                $table->string('published_at')->default(Carbon::now())->nullable();
            });
            Schema::table('prayers', function($table)
            {
                $table->string('published_at')->default(Carbon::now())->nullable();
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
            Schema::table('notes', function($table)
            {
                $table->dropColumn('published_at');
            });
            Schema::table('journal', function($table)
            {
                $table->dropColumn('published_at');
            });
            Schema::table('prayers', function($table)
            {
                $table->dropColumn('published_at');
            });
        });
    }
}
