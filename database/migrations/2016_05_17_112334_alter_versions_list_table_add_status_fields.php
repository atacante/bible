<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVersionsListTableAddStatusFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('versions_list_en', function ($table) {
            $table->boolean('enabled')->default(true)->nullable();
            $table->boolean('enabled_to_compare')->default(true)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('versions_list_en', function ($table) {
            $table->dropColumn('enabled');
            $table->dropColumn('enabled_to_compare');
        });
    }
}
