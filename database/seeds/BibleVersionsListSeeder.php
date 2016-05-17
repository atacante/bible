<?php

use App\BaseModel;
use App\BooksListEn;
use App\Helpers\ProgressBarHelper;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class BibleVersionsListSeeder extends Seeder
{
    private $version;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('TRUNCATE TABLE versions_list_en');
        DB::statement('ALTER SEQUENCE versions_list_en_id_seq RESTART WITH 1');
        VersionsListEn::insert(VersionsListEn::versionsStaticListAll());
    }
}
