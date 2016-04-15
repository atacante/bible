<?php

use App\BooksListEn;
use App\Helpers\ModelHelper;
use App\Helpers\ProgressBarHelper;
use App\LexiconBase;
use App\LexiconBerean;
use App\LexiconKjv;
use App\LexiconNasb;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yangqi\Htmldom\Htmldom;

class NasbLexiconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '768M');
        $excelData = Excel::load(base_path('resources/data/nasb_lexicon_short.xlsx'), function ($reader) {
        })->get();
        $data = [];
        if (count($excelData)) {
            DB::statement("TRUNCATE TABLE lexicon_nasb");
            DB::statement("ALTER SEQUENCE lexicon_nasb_id_seq RESTART WITH 1");

            $progressBar = new ProgressBarHelper(count($excelData), 10);
            $progressBar->start('Started seeding data for NASB lexicon');

            $part = 0;
            $data = [];
            $ignored = 0;
            foreach ($excelData as $key => $row) {
                $part++;
                $matches = 0;
                $book = explode(':',trim($row['verse']));
                $verse_num = $book[1];
                $bookAndChapter = explode(' ',$book[0]);
                $chapter = array_pop($bookAndChapter);
                $book_name = implode(' ',$bookAndChapter);
                $bookObj = BooksListEn::query()->where('book_name',$book_name)->first(['id']);
                $html = new Htmldom(str_replace('|','"',$row['lexicon']));
                $lexiconBase = LexiconBase::
                where('book_id',$bookObj->id)
                    ->where('chapter_num',$chapter)
                    ->where('verse_num',$verse_num)
                    ->orderBy('id')
                    ->get();
                foreach ($lexiconBase as $lexicoItem) {
                    $phrase = $lexicoItem->toArray();
                    unset($phrase['id']);
                    $trs = $html->find('tr');
                    foreach($trs as $trkey => $tr){
                        if($trkey > 0){
                            $tds = $tr->find('td');
                            if($bookObj->id < 40){
                                $verse_compare = $lexicoItem->verse_part_he;
                            }
                            else{
                                $verse_compare = $lexicoItem->verse_part_el;
                            }
                            if(strtolower($verse_compare) == strtolower($tds[1]->plaintext) ||
                                strtolower($lexicoItem->transliteration) == strtolower($tds[2]->plaintext) ||
                                strtolower($lexicoItem->strong_num) == strtolower('H'.intval($tds[3]->plaintext))){
                                $phrase['verse_part'] = utf8_encode($tds[0]->plaintext);
                                $phrase['definition'] = utf8_encode($tds[4]->plaintext);
                                $phrase['origin'] = $tds[5]->plaintext;
                                $matches++;
                                if(strtolower($verse_compare) == strtolower($tds[1]->plaintext) || strtolower($lexicoItem->transliteration) == strtolower($tds[2]->plaintext)){
                                    break;
                                }
                            }
                        }
                    }
                    if(!isset($phrase['verse_part'])){
                        $phrase['verse_part'] = null;
                        $phrase['definition'] = null;
                        $phrase['origin'] = null;
                    }

                    $data[] = $phrase;
                }

                if(count($trs)-1 > $matches){
                    echo $book_name." ".$chapter.":".$verse_num."\n";
                    $ignored++;
                }

                if ($part == 50) {
//                    exit("Process finished. Ignored phrases: ".$ignored);
                    LexiconNasb::insert($data);
                    $data = [];
                    $part = 0;
                }
                $progressBar->update();
            }
            LexiconNasb::insert($data);
            $progressBar->finish("Process finished. Ignored phrases: ".$ignored);
        }
    }
}
