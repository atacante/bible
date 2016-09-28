<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStrongsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::create('strongs_concordance', function (Blueprint $table) {
                $table->increments('id');
                $table->string('dictionary_type');
                $table->string('strong_num');
                $table->string('strong_num_suffix')->nullable();
                $table->string('original_word')->nullable();
                $table->string('transliteration')->nullable();
                $table->string('definition_short')->nullable();
                $table->text('definition_full')->nullable();
                $table->text('exhaustive_concordance')->nullable();
                $table->string('part_of_speech')->nullable();
                $table->string('phonetic_spelling')->nullable();
            });

            Schema::create('strongs_nasec', function (Blueprint $table) {
                $table->increments('id');
                $table->string('dictionary_type');
                $table->string('strong_num');
                $table->string('strong_num_suffix')->nullable();
                $table->text('original_word')->nullable();
                $table->text('definition')->nullable();
                $table->text('nasb_translation')->nullable();
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
            Schema::drop('strongs_concordance');
            Schema::drop('strongs_nasec');
        });
    }
}
