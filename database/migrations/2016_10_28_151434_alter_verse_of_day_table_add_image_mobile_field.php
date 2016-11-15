<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVerseOfDayTableAddImageMobileField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('verse_of_day', function ($table) {
            $table->string('image_mobile')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('verse_of_day', function ($table) {
            $table->dropColumn('image_mobile');
        });
    }
}
