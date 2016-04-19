<?php

use App\BooksListEn;
use App\Helpers\ModelHelper;
use App\Helpers\ProgressBarHelper;
use App\LexiconBase;
use App\LexiconBerean;
use App\LexiconKjv;
use App\LexiconNasb;
use App\StrongsConcordance;
use App\StrongsNasec;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yangqi\Htmldom\Htmldom;

class StrongsNasecSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '768M');
        $excelData = Excel::load(base_path('resources/data/strongs_nasec.xlsx'), function ($reader) {
        })->get();
        $data = [];
        if (count($excelData)) {
            DB::statement("TRUNCATE TABLE strongs_nasec");
            DB::statement("ALTER SEQUENCE strongs_nasec_id_seq RESTART WITH 1");

            $progressBar = new ProgressBarHelper(count($excelData), 10);
            $progressBar->start('Started seeding data for Strongs NASEC');

            $part = 0;
            $data = [];
            foreach ($excelData as $key => $row) {
                $part++;
                $data[$key]['dictionary_type'] = StrongsNasec::DICTIONARY_HEBREW;
                if(trim($row['greek_hebrew']) == "Strong's Greek Dictionary:"){
                    $data[$key]['dictionary_type'] = StrongsNasec::DICTIONARY_GREEK;
                }
                $data[$key]['strong_num'] = intval($row['number']);
                $data[$key]['strong_num_suffix'] = preg_replace('/[0-9]+/', '', $row['number']);
                $data[$key]['original_word'] = $row['word_origin'];
                $data[$key]['definition'] = $row['definition'];
                $data[$key]['nasb_translation'] = $row['nasb_translation'];
                if ($part == 500) {
                    StrongsNasec::insert($data);
                    $data = [];
                    $part = 0;
                }
                $progressBar->update();
            }
            StrongsNasec::insert($data);
            $progressBar->finish();
        }
    }
}
