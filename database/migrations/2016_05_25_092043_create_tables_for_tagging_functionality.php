<?php

use App\Tag;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablesForTaggingFunctionality extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::create('tags', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id')->nullable();
                $table->string('type')->default(Tag::TYPE_SYSTEM);
                $table->string('tag_name');
                $table->timestamps();
            });
            Schema::create('notes_tags', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('note_id');
                $table->integer('tag_id');
            });
            Schema::create('journal_tags', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('journal_id');
                $table->integer('tag_id');
            });
            Schema::create('prayers_tags', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('prayer_id');
                $table->integer('tag_id');
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
            Schema::drop('tags');
            Schema::drop('notes_tags');
            Schema::drop('journal_tags');
            Schema::drop('prayers_tags');
        });
    }
}
