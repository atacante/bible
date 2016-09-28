<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterWallPostsTableAlterTextField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wall_posts', function($table)
        {
            $table->renameColumn('text','status_text');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wall_posts', function($table)
        {
            $table->renameColumn('status_text','text');
        });
    }
}
