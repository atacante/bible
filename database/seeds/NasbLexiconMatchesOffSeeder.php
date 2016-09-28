<?php

use App\BooksListEn;
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

class NasbLexiconMatchesOffSeeder extends Seeder
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
            $progressBar = new ProgressBarHelper((count($excelData)), 10);
            $progressBar->start('Started seeding data for lexicon - NASB');

            DB::statement("TRUNCATE TABLE lexicon_nasb");
            DB::statement("ALTER SEQUENCE lexicon_nasb_id_seq RESTART WITH 1");

            $part = 0;
            $data = [];
            foreach ($excelData as $key => $row) {
                $part++;
                $book = explode(':',trim($row['verse']));
                $verse_num = $book[1];
                $bookAndChapter = explode(' ',$book[0]);
                $chapter = array_pop($bookAndChapter);
                $book_name = implode(' ',$bookAndChapter);
                $bookObj = BooksListEn::query()->where('book_name',$book_name)->first(['id']);
                $html = new Htmldom(str_replace('|','"',$row['lexicon']));
                $trs = $html->find('tr');
                foreach($trs as $trkey => $tr){
                    if($trkey > 0){
                        $tds = $tr->find('td');
                        $data[$trkey]['book_id'] = $bookObj->id;
                        $data[$trkey]['chapter_num'] = $chapter;
                        $data[$trkey]['verse_num'] = $verse_num;
                        $data[$trkey]['strong_num'] = 'H'.intval($tds[3]->plaintext);
                        $data[$trkey]['transliteration'] = $tds[2]->plaintext;
                        if($bookObj->id < 40){
                            $data[$trkey]['verse_part_he'] = $tds[1]->plaintext;
                        }
                        else{
                            $data[$trkey]['verse_part_el'] = $tds[1]->plaintext;
                        }
                        $data[$trkey]['verse_part'] = utf8_encode($tds[0]->plaintext);
                        $data[$trkey]['definition'] = utf8_encode($tds[4]->plaintext);
                        $data[$trkey]['origin'] = $tds[5]->plaintext;
//                        if ($part == 500) {
//                            LexiconBase::insert($data);
//                            $data = [];
//                            $part = 0;
//                        }
                    }
                }
                LexiconNasb::insert($data);
                $data = [];

                $progressBar->update();
            }
            $progressBar->finish('');
        }
    }
}
