<?php

use App\BooksListEn;
use App\Helpers\ModelHelper;
use App\Helpers\ProgressBarHelper;
use App\LexiconBerean;
use App\LexiconKjv;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BereanLexiconMatchesOnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '768M');

        $timeStart = time();

        $csv = new \parseCSV(base_path('resources/data/berean_lexicon_goog.csv'));
        $data = [];
        if (count($csv->data)) {
            $progressBar = new ProgressBarHelper(count($csv->data), 10);
            $progressBar->start('Started seeding data for Berean lexicon');

            DB::statement("TRUNCATE TABLE lexicon_berean");
            DB::statement("ALTER SEQUENCE lexicon_berean_id_seq RESTART WITH 257511");

            ModelHelper::createLexiconStructure('berean');

            $part = 0;
            foreach ($csv->data as $key => $row) {
                if (!empty($row["Transliteration"])) {
                    $part++;
                    $book = explode(':', trim($row['Verse']));
                    $bookAndChapter = explode('|', $book[0]);
                    $chapter = array_pop($bookAndChapter);
                    $book_name = implode(' ', $bookAndChapter);
                    $bookObj = BooksListEn::query()->where('book_name', 'ilike', $book_name)->first(['id']);
                    $verse_num = $book[1];

                    LexiconBerean::$FIRE_EVENTS = false;
                    $lexiconQuery = LexiconBerean::
                    where('book_id', $bookObj->id)
                        ->where('chapter_num', $chapter)
                        ->where('verse_num', $verse_num)
                        ->where('verse_part', null)
                        ->where(function ($q) use ($row, $key) {
                            if (!empty(utf8_encode(strtolower($row['BGB - Berean Greek Bible'])))) {
                                $q->orWhere('verse_part_el', 'ilike', '%' . utf8_encode(strtolower($row['BGB - Berean Greek Bible'])) . '%');
                            }
                            if (!empty(utf8_encode(strtolower($row['Transliteration'])))) {
                                $q->orWhere('transliteration', 'ilike', '%' . utf8_encode(strtolower($row['Transliteration'])) . '%');
                            }
                            if (!empty(utf8_encode($row["Strong's"]))) {
                                $q->orWhere('strong_num', utf8_encode("H" . $row["Strong's"]));
                            }
                        });

                    if ($lexiconQuery->count() > 1) {
                        $lexiconQuery = $lexiconQuery->first();
                    }

                    $phrase['verse_part'] = utf8_encode($row["BLB - Berean Literal Bible"]);
                    $phrase['definition'] = utf8_encode($row["Lexical Definition"]);
                    $phrase['origin'] = null;
                    $lexiconQuery->update($phrase);
                    $phrase = [];

                    $progressBar->update();
                }
            }
//            LexiconBerean::insert($data);
            $progressBar->finish();

            $timeFinish = time();
            echo "Process Time: ".(($timeFinish-$timeStart)/60).' min';
        }
    }
}
