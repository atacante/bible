<?php

use App\CmsPage;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCmsPagesTableAddContentTypeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cms_pages', function($table)
        {
            $table->string('content_type')->default(CmsPage::CONTENT_PAGE);
            $table->string('title')->nullable()->change();
            $table->string('meta_title')->nullable()->change();
            $table->string('meta_keywords')->nullable()->change();
            $table->string('meta_description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cms_pages', function($table)
        {
            $table->dropColumn('content_type');
            $table->string('title')->nullable(false)->change();
            $table->string('meta_title')->nullable(false)->change();
            $table->string('meta_keywords')->nullable(false)->change();
            $table->string('meta_description')->nullable(false)->change();
        });
    }
}
