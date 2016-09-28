<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTableAddInfoFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::transaction(function () {
            Schema::table('users', function ($table) {
                $table->string('church_name')->nullable();
                $table->integer('country_id')->nullable();
                $table->string('state')->nullable();
                $table->string('city')->nullable();
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
            Schema::table('users', function($table)
            {
                $table->dropColumn('church_name');
                $table->dropColumn('country_id');
                $table->dropColumn('state');
                $table->dropColumn('city');
            });
        });
    }
}
