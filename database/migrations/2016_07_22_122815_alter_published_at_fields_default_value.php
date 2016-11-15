<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPublishedAtFieldsDefaultValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            Schema::table('notes', function ($table) {
//                $table->timestamp('test_timestamp')->useCurrent()->nullable();
                DB::statement('ALTER TABLE public.notes ALTER COLUMN published_at DROP DEFAULT;');
                DB::statement('ALTER TABLE public.notes ALTER COLUMN published_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING published_at::timestamp(0) without time zone;');
                DB::statement('ALTER TABLE public.notes ALTER COLUMN published_at SET DEFAULT CURRENT_TIMESTAMP;');
            });
            Schema::table('journal', function ($table) {
//                $table->timestamp('published_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable()->change();
                DB::statement('ALTER TABLE public.journal ALTER COLUMN published_at DROP DEFAULT;');
                DB::statement('ALTER TABLE public.journal ALTER COLUMN published_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING published_at::timestamp(0) without time zone;');
                DB::statement('ALTER TABLE public.journal ALTER COLUMN published_at SET DEFAULT CURRENT_TIMESTAMP;');
            });
            Schema::table('prayers', function ($table) {
//                $table->timestamp('published_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable()->change();
                DB::statement('ALTER TABLE public.prayers ALTER COLUMN published_at DROP DEFAULT;');
                DB::statement('ALTER TABLE public.prayers ALTER COLUMN published_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING published_at::timestamp(0) without time zone;');
                DB::statement('ALTER TABLE public.prayers ALTER COLUMN published_at SET DEFAULT CURRENT_TIMESTAMP;');
            });
            Schema::table('wall_posts', function ($table) {
//                $table->timestamp('published_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable()->change();
                DB::statement('ALTER TABLE public.wall_posts ALTER COLUMN published_at DROP DEFAULT;');
                DB::statement('ALTER TABLE public.wall_posts ALTER COLUMN published_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING published_at::timestamp(0) without time zone;');
                DB::statement('ALTER TABLE public.wall_posts ALTER COLUMN published_at SET DEFAULT CURRENT_TIMESTAMP;');
            });
            Schema::table('blog_articles', function ($table) {
//                $table->timestamp('published_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable()->change();
                DB::statement('ALTER TABLE public.blog_articles ALTER COLUMN published_at DROP DEFAULT;');
                DB::statement('ALTER TABLE public.blog_articles ALTER COLUMN published_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING published_at::timestamp(0) without time zone;');
                DB::statement('ALTER TABLE public.blog_articles ALTER COLUMN published_at SET DEFAULT CURRENT_TIMESTAMP;');
            });
            Schema::table('blog_comments', function ($table) {
//                $table->timestamp('published_at')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable()->change();
                DB::statement('ALTER TABLE public.blog_comments ALTER COLUMN published_at DROP DEFAULT;');
                DB::statement('ALTER TABLE public.blog_comments ALTER COLUMN published_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE USING published_at::timestamp(0) without time zone;');
                DB::statement('ALTER TABLE public.blog_comments ALTER COLUMN published_at SET DEFAULT CURRENT_TIMESTAMP;');
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
        //
    }
}
