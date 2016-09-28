<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->boolean('got_chapter_diff_tooltip')->default(false);
            $table->boolean('got_verse_diff_tooltip')->default(false);
            $table->boolean('got_related_records_tooltip')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('notifications_settings');
    }
}
