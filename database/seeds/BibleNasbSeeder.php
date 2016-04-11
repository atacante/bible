<?php

use App\BaseModel;
use App\BooksListEn;
use App\Helpers\ProgressBarHelper;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class BibleNasbSeeder extends Seeder
{
    private $version = 'nasb';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '512M');
        $csv = new \parseCSV(base_path('resources/data/nasb_bible_short.csv'));
        $data = [];
        if (count($csv->data)) {
            $progressBar = new ProgressBarHelper(count($csv->data),10);
            $progressBar->start('Started seeding data for NASB version');

            DB::statement('TRUNCATE TABLE verses_' . $this->version . '_en');
            DB::statement('ALTER SEQUENCE verses_' . $this->version . '_en_id_seq RESTART WITH 1');
            foreach ($csv->data as $row) {
                $book = explode(':', trim($row['Verse']));
                $bookAndChapter = explode(' ', $book[0]);
                $chapter = array_pop($bookAndChapter);
                $verse_num = $book[1];
                $book = implode(' ', $bookAndChapter);//array_pop() result
                $verseText = str_replace('|','"',$row["New American Standard Bible"]);

                $verse = [
                    'book_id' => BooksListEn::where('book_name', $book)->first()->id,
                    'chapter_num' => $chapter,
                    'verse_num' => $verse_num,
                    'verse_text' => utf8_encode($verseText)
                ];

                $locale = Config::get('app.locale');// temporary static variable
                $modelName = BaseModel::getModelByTableName('verses_' . $this->version . '_' . $locale);
                $modelName::insert($verse);

                $progressBar->update();
            }
            $progressBar->finish();
        }
    }
}
