<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTableWithData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::create('countries', function (Blueprint $table) {
                $table->increments('id');
                $table->string('iso', 2);
                $table->string('name', 80);
                $table->string('nicename', 80);
                $table->string('iso3', 3)->nullable();
                $table->integer('numcode')->nullable();
                $table->integer('phonecode');
            });
            DB::unprepared(File::get(base_path('resources/data/countries.sql')));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('countries');
    }
}
