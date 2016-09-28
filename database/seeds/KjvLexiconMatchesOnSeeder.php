<?php

use App\BooksListEn;
use App\Helpers\ModelHelper;
use App\Helpers\ProgressBarHelper;
use App\LexiconBase;
use App\LexiconKjv;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class KjvLexiconMatchesOnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '1024M');

        $timeStart = time();

        $csv = new \parseCSV(base_path('resources/data/lexicon_short.csv'));
        $he = new \parseCSV(base_path('resources/data/kjv_lexicon_only_he.csv'));
//        $excelData = Excel::load(base_path('resources/data/kjv_lexicon_only_he.xlsx'), function ($reader) {})->get();
        $data = [];
        if(count($csv->data) && count($csv->data) == count($he->data)){

            $progressBar = new ProgressBarHelper(count($csv->data),10);
            $progressBar->start('Started seeding data for KVJ lexicon');

            DB::statement("TRUNCATE TABLE lexicon_kjv");
            DB::statement("ALTER SEQUENCE lexicon_kjv_id_seq RESTART WITH 1");

            ModelHelper::createLexiconStructure('kjv');

            $part = 0;
            foreach($csv->data as $key => $row){
//                $timeStart = round(microtime(true) * 1000);
                $part++;
                $book = explode(':',trim($row['KJV Verse']));
                $bookAndChapter = explode(' ',$book[0]);
                $chapter = array_pop($bookAndChapter);
                $book_name = implode(' ',$bookAndChapter);
                $bookObj = BooksListEn::query()->where('book_name',$book_name)->first(['id']);
                $verse_num = $book[1];
                LexiconKjv::$FIRE_EVENTS = false;
                $lexiconQuery = LexiconKjv::
                      where('book_id',$bookObj->id)
                    ->where('chapter_num',$chapter)
                    ->where('verse_num',$verse_num)
                    ->where('verse_part',NULL)
                    ->where(function($q) use($row,$he,$key) {
                        if(!empty(utf8_encode(strtolower($he->data[$key]['WLC - Troidl and Kimball'])))){
                            $q->orWhere('verse_part_he', 'ilike', '%'.utf8_encode(strtolower($he->data[$key]['WLC - Troidl and Kimball'])).'%');
                        }
                        if(!empty(utf8_encode(strtolower($row['Translit Romanized'])))){
                            $q->orWhere('transliteration', 'ilike', '%'.utf8_encode(strtolower($row['Translit Romanized'])).'%');
                        }
                        if(!empty(utf8_encode($row["Strong's - Troidl and Kimball - http://openscriptures.org"]))){
//                          $q->orWhere('strong_num', 'ilike','%'.utf8_encode($row["Strong's - Troidl and Kimball - http://openscriptures.org"]).'%');
                            $q->orWhere('strong_num',utf8_encode($row["Strong's - Troidl and Kimball - http://openscriptures.org"]));
                        }
//                                $q->whereRaw("Cast(verse_part_he AS Nvarchar(max))like N'".$tds[1]->plaintext."%'");
                    });

                if($lexiconQuery->count() > 1){
                    $lexiconQuery = $lexiconQuery->first();
                }

                $phrase['verse_part'] = utf8_encode($row["kjv + Other Sources"]);
                $phrase['definition'] = utf8_encode($row["Strong's 1-word def"]);
                $phrase['origin'] = null;
                /*if($bookObj->id == 1 && $chapter == 1 && $verse_num == 5){
                    var_dump(utf8_encode($row['Translit Romanized']));
                }*/
                $lexiconQuery->update($phrase);
                $phrase = [];
                /*foreach ($lexiconBase as $lexicoItem) {
                    $phrase = $lexicoItem->toArray();
                    unset($phrase['id']);
                    if(strtolower($lexicoItem->verse_part_he) == strtolower(utf8_encode($he->data[$key]['WLC - Troidl and Kimball'])) ||
                        strtolower($lexicoItem->transliteration) == strtolower(utf8_encode($row['Translit Romanized'])) ||
                        strtolower($lexicoItem->strong_num) == strtolower(utf8_encode($row["Strong's - Troidl and Kimball - http://openscriptures.org"]))){
                        $phrase['verse_part'] = utf8_encode($row["kjv + Other Sources"]);
                        $phrase['definition'] = utf8_encode($row["Strong's 1-word def"]);
                        $phrase['origin'] = null;
                        $matches++;
                        if(strtolower($lexicoItem->verse_part_he) == strtolower(utf8_encode($he->data[$key]['WLC - Troidl and Kimball'])) || strtolower($lexicoItem->transliteration) == strtolower(utf8_encode($row['Translit Romanized']))){
                            break;
                        }
                    }
                }
                if(!isset($phrase['verse_part'])){
                    $phrase['verse_part'] = '';
                    $phrase['definition'] = null;
                    $phrase['origin'] = null;
                }
                LexiconKjv::insert($phrase);*/

//                $data[] = $phrase;
//
//                if($part == 500){
//                    LexiconKjv::insert($data);
//                    $data = [];
//                    $part = 0;
//                }
                $progressBar->update();

//                $timeFinish = round(microtime(true) * 1000);
//                echo "Process Time: ".(($timeFinish-$timeStart)).' ms'."\n";
            }
//            LexiconKjv::insert($data);
            $progressBar->finish();

            $timeFinish = time();
            echo "Process Time: ".(($timeFinish-$timeStart)/60).' min';
        }
    }
}
