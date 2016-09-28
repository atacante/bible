<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersTableAddTimestampColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->boolean('is_online')->default(false);
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('upgraded_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('is_online');
            $table->dropColumn('last_login_at');
            $table->dropColumn('upgraded_at');
        });
    }
}
