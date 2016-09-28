<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLexiconTablesAddOrigin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::table('lexicon_kjv', function ($table) {
                $table->text('origin')->nullable();
            });
            Schema::table('lexicon_berean', function ($table) {
                $table->text('origin')->nullable();
            });
            Schema::table('lexicon_nasb', function ($table) {
                $table->text('origin')->nullable();
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
            Schema::table('lexicon_kjv', function ($table) {
                $table->dropColumn('origin');
            });
            Schema::table('lexicon_berean', function ($table) {
                $table->dropColumn('origin');
            });
            Schema::table('lexicon_nasb', function ($table) {
                $table->dropColumn('origin');
            });
        });
    }
}
