<?php

use App\WallPost;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWallPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wall_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->default(WallPost::TYPE_STATUS);
            $table->string('wall_type');
            $table->integer('user_id');
            $table->integer('verse_id')->nullable();
            $table->integer('rel_id')->nullable();
            $table->text('text');
            $table->string('access_level')->default(WallPost::ACCESS_PUBLIC_ALL);
            $table->string('published_at')->default(Carbon::now())->nullable();
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
        Schema::drop('wall_posts');
    }
}
