<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLocationVerseTableRemoveExtraFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('location_verse', function ($table) {
            $table->dropColumn('book_id');
            $table->dropColumn('chapter_num');
            $table->dropColumn('verse_num');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('location_verse', function ($table) {
            $table->integer('book_id')->nullable();
            $table->integer('chapter_num')->nullable();
            $table->integer('verse_num')->nullable();
        });
    }
}
