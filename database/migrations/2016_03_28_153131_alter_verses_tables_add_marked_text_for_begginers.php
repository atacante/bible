<?php

use App\VersionsListEn;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVersesTablesAddMarkedTextForBegginers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            $versions = VersionsListEn::versionsList();
            if ($versions) {
                foreach ($versions as $version) {
                    $this->version = $version['version_code'];
                    $table = 'verses_' . $this->version . '_en';
                    if (Schema::hasTable($table))
                    {
                        Schema::table($table, function(Blueprint $table)
                        {
                            $table->text('verse_text_with_symbolism')->nullable();
                        });
                    }
                }
            }
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
            $versions = VersionsListEn::versionsList();
            if ($versions) {
                foreach ($versions as $version) {
                    $this->version = $version['version_code'];
                    $table = 'verses_' . $this->version . '_en';
                    if (Schema::hasTable($table)) {
                        Schema::table($table, function(Blueprint $table) {
                            $table->dropColumn('verse_text_with_symbolism');
                        });
                    }
                }
            }
        });
    }
}
