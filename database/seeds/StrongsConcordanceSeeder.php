<?php

use App\BooksListEn;
use App\Helpers\ModelHelper;
use App\Helpers\ProgressBarHelper;
use App\LexiconBase;
use App\LexiconBerean;
use App\LexiconKjv;
use App\LexiconNasb;
use App\StrongsConcordance;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yangqi\Htmldom\Htmldom;

class StrongsConcordanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '768M');
        $excelData = Excel::load(base_path('resources/data/strongs_concord.xlsx'), function ($reader) {
        })->get();
        $data = [];
        if (count($excelData)) {
            DB::statement("TRUNCATE TABLE strongs_concordance");
            DB::statement("ALTER SEQUENCE strongs_concordance_id_seq RESTART WITH 1");

            $progressBar = new ProgressBarHelper(count($excelData), 10);
            $progressBar->start('Started seeding data for Strongs Concordance');

            $part = 0;
            $data = [];
            foreach ($excelData as $key => $row) {
                $part++;
                $data[$key]['dictionary_type'] = StrongsConcordance::DICTIONARY_HEBREW;
                if(trim($row['greek_hebrew']) == "Strong's Greek Dictionary:"){
                    $data[$key]['dictionary_type'] = StrongsConcordance::DICTIONARY_GREEK;
                }
                $data[$key]['strong_num'] = intval($row['number']);
                $data[$key]['strong_num_suffix'] = preg_replace('/[0-9]+/', '', $row['number']);
                $data[$key]['original_word'] = $row['original'];
                $data[$key]['transliteration'] = $row['word'];
                $data[$key]['definition_short'] = $row['tag'];
                $data[$key]['definition_full'] = $row['meaning'];
                $data[$key]['exhaustive_concordance'] = $row['strongs_exhaustive_concordance'];
                if ($part == 500) {
                    StrongsConcordance::insert($data);
                    $data = [];
                    $part = 0;
                }
                $progressBar->update();
            }
            StrongsConcordance::insert($data);
            $progressBar->finish();
        }
    }
}
