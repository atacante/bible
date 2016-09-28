<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLexiconTablesAddNewColumns extends Migration
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
                $table->text('definition')->nullable();
                $table->text('verse_part_el')->nullable();
                $table->text('verse_part_he')->nullable();
                $table->string('verse_part')->nullable()->change();
                $table->string('strong_num')->nullable()->change();
                $table->string('strong_1_word_def')->nullable()->change();
                $table->string('transliteration')->nullable()->change();
            });
            Schema::table('lexicon_berean', function ($table) {
                $table->text('definition')->nullable();
                $table->text('verse_part_el')->nullable();
                $table->text('verse_part_he')->nullable();
                $table->string('verse_part')->nullable()->change();
                $table->string('strong_num')->nullable()->change();
                $table->string('strong_1_word_def')->nullable()->change();
                $table->string('transliteration')->nullable()->change();
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
                $table->dropColumn('definition');
                $table->dropColumn('verse_part_el');
                $table->dropColumn('verse_part_he');
            });
            Schema::table('lexicon_berean', function ($table) {
                $table->dropColumn('definition');
                $table->dropColumn('verse_part_el');
                $table->dropColumn('verse_part_he');
            });
        });
    }
}
