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

class BaseLexiconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '768M');
        $oldTestament = new \parseCSV(base_path('resources/data/lexicon_short.csv'));
        $oldTestamentHe = new \parseCSV(base_path('resources/data/kjv_lexicon_only_he.csv'));
//        $newTestament = new \parseCSV(base_path('resources/data/berean_lexicon_goog.csv'));
        $newTestament = [1];
        $data = [];
        if (count($oldTestament->data) && count($oldTestament->data) == count($oldTestamentHe->data) && count($newTestament->data)) {
            $progressBar = new ProgressBarHelper((count($oldTestament->data)+count($newTestament->data)), 10);
            $progressBar->start('Started seeding data for lexicon BASE');

            DB::statement("TRUNCATE TABLE lexicon_base");
            DB::statement("ALTER SEQUENCE lexicon_base_id_seq RESTART WITH 1");

            /* Process old testament */
            $part = 0;
            $data = [];
            foreach ($oldTestament->data as $key => $row) {
                $part++;
                $book = explode(':',trim($row['KJV Verse']));
                $bookAndChapter = explode(' ',$book[0]);
                $chapter = array_pop($bookAndChapter);
                $book_name = implode(' ',$bookAndChapter);
                $bookObj = BooksListEn::query()->where('book_name',$book_name)->first(['id']);
                $verse_num = $book[1];

                $data[$key]['book_id'] = $bookObj->id;
                $data[$key]['chapter_num'] = $chapter;
                $data[$key]['verse_num'] = $verse_num;
                $data[$key]['strong_num'] = utf8_encode($row["Strong's - Troidl and Kimball - http://openscriptures.org"]);
                $data[$key]['transliteration'] = utf8_encode($row['Translit Romanized']);
                $data[$key]['verse_part_he'] = mb_convert_encoding($oldTestamentHe->data[$key]['WLC - Troidl and Kimball'],'UTF-8');

                if ($part == 500) {
                    LexiconBase::insert($data);
                    $data = [];
                    $part = 0;
                }
                $progressBar->update();
            }
            LexiconBase::insert($data);

            /* Process new testament */
            /*$part = 0;
            $data = [];
            foreach ($newTestament->data as $key => $row) {
                if(!empty($row["Transliteration"])) {
                    $part++;
                    $book = explode(':', trim($row['Verse']));
                    $bookAndChapter = explode('|', $book[0]);
                    $chapter = array_pop($bookAndChapter);
                    $book_name = implode(' ', $bookAndChapter);
                    $bookObj = BooksListEn::query()->where('book_name', 'ilike', $book_name)->first(['id']);
                    $verse_num = $book[1];

                    $data[$key]['book_id'] = $bookObj->id;
                    $data[$key]['chapter_num'] = $chapter;
                    $data[$key]['verse_num'] = $verse_num;
                    $data[$key]['strong_num'] = utf8_encode("H" . $row["Strong's"]);
                    $data[$key]['transliteration'] = $row['Transliteration'];
                    $data[$key]['verse_part_el'] = mb_convert_encoding($row['BGB - Berean Greek Bible'],'UTF-8');

                    if ($part == 500) {
                        LexiconBase::insert($data);
                        $data = [];
                        $part = 0;
                    }
                }
                $progressBar->update();
            }
            LexiconBase::insert($data);*/

            $progressBar->finish();
        }
    }
}
