<?php

use App\VersionsListEng;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVersionsListEngTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('versions_list_eng', function($table)
        {
            $table->string('version_code', 255)->default('');
        });
        $versions = VersionsListEng::all();
        if($versions){
            foreach($versions as $version){
                $version->version_code = str_replace(' ','_',strtolower($version->version_name));
                $version->save();
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
        Schema::table('versions_list_eng', function($table)
        {
            $table->dropColumn('version_code');
        });
    }
}
