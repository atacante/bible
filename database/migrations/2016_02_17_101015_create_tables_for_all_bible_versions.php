<?php

use App\VersionsListEn;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablesForAllBibleVersions extends Migration
{
    private $version;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('verses_american_standard_en');
        $versions = VersionsListEn::versionsList();
        if ($versions) {
            foreach ($versions as $version) {
                $this->version = $version['version_code'];
                Schema::create('verses_' . $this->version . '_en', function (Blueprint $table) {
                    $table->increments('id');
                    $table->integer('book_id');
                    $table->integer('chapter_num');
                    $table->integer('verse_num');
                    $table->text('verse_text')->nullable();
                });
                Schema::table('verses_' . $this->version . '_en', function (Blueprint $table) {
                    $table->foreign('book_id', 'verses_' . $this->version . '_en_fk')->references('id')->on('books_list_en')->onUpdate('RESTRICT')->onDelete('RESTRICT');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $versions = VersionsListEn::versionsList();
        if ($versions) {
            foreach ($versions as $version) {
                $this->version = $version['version_code'];
                if (Schema::hasTable('verses_' . $this->version . '_en'))
                {
                    Schema::drop('verses_' . $this->version . '_en');
                }
            }
        }
        Schema::create('verses_american_standard_en', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('book_id');
            $table->integer('chapter_num');
            $table->integer('verse_num');
            $table->text('verse_text')->nullable();
        });
        Schema::table('verses_american_standard_en', function(Blueprint $table)
        {
            $table->foreign('book_id', 'verses_american_standard_en_fk')->references('id')->on('books_list_en')->onUpdate('RESTRICT')->onDelete('RESTRICT');
        });
    }
}
