<?php

use App\VersionsListEn;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVersesTables extends Migration
{
    private $version;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function () {
            $versions = VersionsListEn::versionsListAll();
            if ($versions) {
                foreach ($versions as $version) {
                    $this->version = $version['version_code'];
                    $table = 'verses_' . $this->version . '_en';
                    if (Schema::hasTable($table))
                    {
                        DB::statement('ALTER TABLE ' .$table. ' ADD COLUMN searchtext TSVECTOR;');
                        DB::statement('UPDATE ' .$table. ' SET searchtext = to_tsvector(\'english\', verse_text);');
                        DB::statement('CREATE INDEX searchtext_'.$table.'_gin ON ' .$table. ' USING GIN(searchtext);');
                        DB::statement('CREATE TRIGGER ts_searchtext BEFORE INSERT OR UPDATE ON ' .$table. ' FOR EACH ROW EXECUTE PROCEDURE tsvector_update_trigger(\'searchtext\', \'pg_catalog.english\', \'verse_text\')');
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
            $versions = VersionsListEn::versionsListAll();
            if ($versions) {
                foreach ($versions as $version) {
                    $this->version = $version['version_code'];
                    $table = 'verses_' . $this->version . '_en';
                    if (Schema::hasTable($table))
                    {
                        DB::statement('ALTER TABLE ' .$table. '  DROP COLUMN searchtext;');
                        DB::statement('DROP TRIGGER ts_searchtext ON ' .$table);
                    }
                }
            }
        });
    }
}
