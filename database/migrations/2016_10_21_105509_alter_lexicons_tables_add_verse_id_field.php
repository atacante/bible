<?php

use App\LexiconBerean;
use App\LexiconKjv;
use App\LexiconNasb;
use App\VersesNasbEn;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLexiconsTablesAddVerseIdField extends Migration
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
                $table->integer('verse_id')->nullable();
            });
            Schema::table('lexicon_berean', function ($table) {
                $table->integer('verse_id')->nullable();
            });
            Schema::table('lexicon_nasb', function ($table) {
                $table->integer('verse_id')->nullable();
            });
            /*$verses = VersesNasbEn::get(['id','book_id','chapter_num','verse_num']);
            foreach ($verses as $verse) {
                LexiconNasb::
                where('book_id',$verse->book_id)
                    ->where('chapter_num',$verse->chapter_num)
                    ->where('verse_num',$verse->verse_num)
                    ->update(['verse_id' => $verse->id]);
                LexiconKjv::
                where('book_id',$verse->book_id)
                    ->where('chapter_num',$verse->chapter_num)
                    ->where('verse_num',$verse->verse_num)
                    ->update(['verse_id' => $verse->id]);
                LexiconBerean::
                where('book_id',$verse->book_id)
                    ->where('chapter_num',$verse->chapter_num)
                    ->where('verse_num',$verse->verse_num)
                    ->update(['verse_id' => $verse->id]);
            }*/
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
                $table->dropColumn('verse_id');
            });
            Schema::table('lexicon_berean', function ($table) {
                $table->dropColumn('verse_id');
            });
            Schema::table('lexicon_nasb', function ($table) {
                $table->dropColumn('verse_id');
            });
        });
    }
}
