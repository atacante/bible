<?php

use App\BooksListEn;
use App\Helpers\ModelHelper;
use App\Helpers\ProgressBarHelper;
use App\LexiconBerean;
use App\LexiconKjv;
use App\LexiconNasb;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yangqi\Htmldom\Htmldom;

class NasbLexiconSeederSlow extends Seeder
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

            ModelHelper::createLexiconStructure('nasb');

            $progressBar = new ProgressBarHelper(count($excelData), 10);
            $progressBar->start('Started seeding data for NASB lexicon');

            $part = 0;
            $phrase = 0;
            $ignored = 0;
            foreach ($excelData as $key => $row) {
                $part++;
                $book = explode(':',trim($row['verse']));
                $verse_num = $book[1];
                $bookAndChapter = explode(' ',$book[0]);
                $chapter = array_pop($bookAndChapter);
                $book_name = implode(' ',$bookAndChapter);
                $bookObj = BooksListEn::query()->where('book_name',$book_name)->first(['id']);
                $html = new Htmldom(str_replace('|','"',$row['lexicon']));
                foreach($html->find('tr') as $trkey => $tr){
                    if($trkey > 0){
                        $phrase++;
                        $tds = $tr->find('td');
//                        $data['book_id'] = $bookObj->id;
//                        $data['chapter_num'] = $chapter;
//                        $data['verse_num'] = $verse_num;
                        $data['verse_part'] = utf8_encode($tds[0]->plaintext);
//                        $data['strong_num'] = utf8_encode("H" . intval($tds[3]->plaintext));
                        $data['definition'] = utf8_encode($tds[4]->plaintext);
//                        $data['transliteration'] = $tds[2]->plaintext;
                        $data['origin'] = $tds[5]->plaintext;

                        $lexiconQuery =  LexiconNasb::where('book_id', $bookObj->id)->where('chapter_num', $chapter)->where('verse_num', $verse_num);

                        if($bookObj->id < 40){
//                            $lexiconQuery->where('verse_part_he', $tds[1]->plaintext);
                            $lexiconQuery->where(function($q) use($tds,$data) {
                                $q->orWhere('verse_part_he', 'ilike', '%'.$tds[1]->plaintext.'%');
                                $q->orWhere('transliteration', 'ilike', '%'.$data['transliteration'].'%');
                                $q->orWhere('strong_num', 'ilike','%H'.intval($tds[3]->plaintext).'%');
//                                $q->whereRaw("Cast(verse_part_he AS Nvarchar(max))like N'".$tds[1]->plaintext."%'");
                            });
                        }
                        else{
                            $lexiconQuery->where('verse_part_el','ilike', $tds[1]->plaintext);
                        }
                        if(!$lexiconQuery->update($data)){
                            $ignored++;
                        }
                    }
                }
//                if ($part == 500) {
//                    LexiconNasb::insert($data);
//                    $data = [];
//                    $part = 0;
//                }
                $progressBar->update();
            }
//            LexiconNasb::insert($data);
            $progressBar->finish("Process finished. Ignored phrases: ".$ignored);
        }
    }
}
