<?php

use App\BooksListEn;
use App\Helpers\ProgressBarHelper;
use App\LexiconKjv;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LexiconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '1024M');
        $csv = new \parseCSV(base_path('resources/data/lexicon_short.csv'));
        $he = new \parseCSV(base_path('resources/data/kjv_lexicon_only_he.csv'));
//        $excelData = Excel::load(base_path('resources/data/kjv_lexicon_only_he.xlsx'), function ($reader) {})->get();
        $data = [];
        if(count($csv->data) && count($csv->data) == count($he->data)){
            $progressBar = new ProgressBarHelper(count($csv->data),10);
            $progressBar->start('Started seeding data for KVJ lexicon');
            DB::statement("TRUNCATE TABLE lexicon_kjv");
            DB::statement("ALTER SEQUENCE lexicon_kjv_id_seq RESTART WITH 1");
            $part = 0;
            foreach($csv->data as $key => $row){
//                if(!empty($row["kjv + Other Sources"]) && $row["Strong's - Troidl and Kimball - http://openscriptures.org"] != 'H0'){
                    $part++;
                    $book = explode(':',trim($row['KJV Verse']));
                    $bookAndChapter = explode(' ',$book[0]);
                    $chapter = array_pop($bookAndChapter);
                    $book_name = implode(' ',$bookAndChapter);
                    $bookObj = BooksListEn::query()->where('book_name',$book_name)->first(['id']);
                    if(!$bookObj){
//                        var_dump($bookAndChapter);//array_pop() result
//                        var_dump($row);
                    }
                    $verse_num = $book[1];

                    $data[$key]['book_id'] = $bookObj->id;
                    $data[$key]['chapter_num'] = $chapter;
                    $data[$key]['verse_num'] = $verse_num;
                    $data[$key]['verse_part'] = utf8_encode($row["kjv + Other Sources"]);
                    $data[$key]['strong_num'] = utf8_encode($row["Strong's - Troidl and Kimball - http://openscriptures.org"]);
                    $data[$key]['strong_1_word_def'] = utf8_encode($row["Strong's 1-word def"]);
                    $data[$key]['definition'] = utf8_encode($row["Strong's 1-word def"]);
                    $data[$key]['transliteration'] = utf8_encode($row['Translit Romanized']);
                    $data[$key]['verse_part_he'] = $he->data[$key]['WLC - Troidl and Kimball'];
//                }

                if($part == 500){
                    LexiconKjv::insert($data);
                    $data = [];
                    $part = 0;
                }
                $progressBar->update();
            }
            LexiconKjv::insert($data);
            $progressBar->finish();
        }
    }
}
