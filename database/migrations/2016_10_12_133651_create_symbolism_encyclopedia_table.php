<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSymbolismEncyclopediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('symbolism_encyclopedia', function (Blueprint $table) {
            $table->increments('id');
            $table->string('term_name');
            $table->text('term_description');
            $table->boolean('associate_lexicons')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('symbolism_encyclopedia');
    }
}
