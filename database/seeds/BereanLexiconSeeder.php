<?php

use App\BooksListEn;
use App\Helpers\ProgressBarHelper;
use App\LexiconBerean;
use App\LexiconKjv;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BereanLexiconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '768M');
        $csv = new \parseCSV(base_path('resources/data/berean_lexicon_goog.csv'));
        $data = [];
        if (count($csv->data)) {
            $progressBar = new ProgressBarHelper(count($csv->data),10);
            $progressBar->start('Started seeding data for Berean lexicon');

            DB::statement("TRUNCATE TABLE lexicon_berean");
            DB::statement("ALTER SEQUENCE lexicon_berean_id_seq RESTART WITH 312513");
            $part = 0;
            foreach ($csv->data as $key => $row) {
                if(!empty($row["Transliteration"])){
                    $part++;
                    $book = explode(':', trim($row['Verse']));
                    $bookAndChapter = explode('|', $book[0]);
                    $chapter = array_pop($bookAndChapter);
                    $book_name = implode(' ', $bookAndChapter);
                    $bookObj = BooksListEn::query()->where('book_name','ilike', $book_name)->first(['id']);

                    $verse_num = $book[1];

//                    var_dump($row["Verse"]);

                    $data[$key]['book_id'] = $bookObj->id;
                    $data[$key]['chapter_num'] = $chapter;
                    $data[$key]['verse_num'] = $verse_num;
                    $data[$key]['verse_part'] = utf8_encode($row["BLB - Berean Literal Bible"]);
                    $data[$key]['strong_num'] = utf8_encode("H" . $row["Strong's"]);
                    $data[$key]['definition'] = utf8_encode($row["Lexical Definition"]);
//                    $data[$key]['transliteration'] = utf8_encode($row['Transliteration']);
//                    $data[$key]['verse_part_el'] = utf8_encode($row['BGB - Berean Greek Bible']);
                    $data[$key]['transliteration'] = $row['Transliteration'];
                    $data[$key]['verse_part_el'] = $row['BGB - Berean Greek Bible'];

                    if ($part == 500) {
                        LexiconBerean::insert($data);
                        $data = [];
                        $part = 0;
                    }
                }
                $progressBar->update();
            }
            LexiconBerean::insert($data);
            $progressBar->finish();
        }
    }
}
