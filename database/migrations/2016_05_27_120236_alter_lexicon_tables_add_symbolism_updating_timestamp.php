<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLexiconTablesAddSymbolismUpdatingTimestamp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::table('lexicon_berean', function ($table) {
                $table->timestamp('symbolism_updated_at')->nullable();
            });
            Schema::table('lexicon_kjv', function ($table) {
                $table->timestamp('symbolism_updated_at')->nullable();
            });
            Schema::table('lexicon_nasb', function ($table) {
                $table->timestamp('symbolism_updated_at')->nullable();
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
            Schema::table('lexicon_berean', function ($table) {
                $table->dropColumn('symbolism_updated_at');
            });
            Schema::table('lexicon_kjv', function ($table) {
                $table->dropColumn('symbolism_updated_at');
            });
            Schema::table('lexicon_nasb', function ($table) {
                $table->dropColumn('symbolism_updated_at');
            });
        });
    }
}
