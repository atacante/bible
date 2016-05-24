<?php

use App\BaseModel;
use App\BooksListEn;
use App\Helpers\ProgressBarHelper;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class BibleAllVersionsSeeder extends Seeder
{
    private $version;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '512M');
        $csv = new \parseCSV(base_path('resources/data/bibles.csv'));
        $data = [];
        if(count($csv->data)){
            /* !!!IMPORTANT!!!  "version_name" value should match with headers in bibles.csv during seeding data */
            $progressBar = new ProgressBarHelper(count($csv->data)*9, 10);
            $progressBar->start('Started seeding data for 9 Bible versions');

            $versions = $this->versionsList();
            if ($versions) {
                foreach ($versions as $version) {
                    $this->version = $version['version_code'];
                    if (Schema::hasTable('verses_' . $this->version . '_en'))
                    {
                        DB::statement('TRUNCATE TABLE verses_' . $this->version . '_en');
                        DB::statement('ALTER SEQUENCE verses_' . $this->version . '_en_id_seq RESTART WITH 1');
                    }
                }
            }
//            DB::statement("TRUNCATE TABLE versions_list_en");
            DB::statement("TRUNCATE TABLE books_list_en CASCADE");

//            DB::statement("ALTER SEQUENCE versions_list_en_id_seq RESTART WITH 1");
            DB::statement("ALTER SEQUENCE books_list_en_id_seq RESTART WITH 1");

//            VersionsListEn::create(['version_name' => 'American Standard Version','version_code' => str_replace(' ','_',strtolower('American Standard Version'))]);
            $prevBook = '';
            $bookId = 0;
            foreach($csv->data as $row){
                foreach ($versions as $version) {
                    $this->version = $version['version_name'];

                    $book = explode(':',trim($row['Verse']));
                    $bookAndChapter = explode(' ',$book[0]);
                    $chapter = array_pop($bookAndChapter);
                    $verse_num = $book[1];
                    $book = implode(' ',$bookAndChapter);//array_pop() result
                    $verse = $row[$this->version];
                    if($book != $prevBook){
                        $bookModel = BooksListEn::create(['id' => $bookId+1,'book_name' => $book]);
                        $prevBook = $book;
                        $bookId = $bookModel->id;
                    }

                    $verse = [
                        'book_id' => $bookId,
                        'chapter_num' => $chapter,
                        'verse_num' => $verse_num,
                        'verse_text' => utf8_encode($verse)
                    ];

                    $locale = Config::get('app.locale');// temporary static variable
                    $modelName = BaseModel::getModelByTableName('verses_'.$version['version_code'].'_'.$locale);
                    $modelName::insert($verse);
                    $progressBar->update();
                }
            }
            $progressBar->finish();
        }
    }

    private function versionsList()
    {
        /*
            !!!IMPORTANT!!!
            Changing "version_code" value requires changing corresponding DB tables names. Tables format: "verses_[version_code]_[lang]"
            "version_name" value should match with headers in bibles.csv during seeding data
        */
        return [
            [
                'version_name' => 'American Standard Version',
                'version_code' => 'american_standard',
                'enabled'      => false,
                'enabled_to_compare'      => true,
            ],
            [
                'version_name' => 'King James Bible',
                'version_code' => 'king_james',
                'enabled'      => true,
                'enabled_to_compare'      => true,
            ],
            [
                'version_name' => 'Douay-Rheims Bible',
                'version_code' => 'douay_rheims',
                'enabled'      => false,
                'enabled_to_compare'      => true,
            ],
            [
                'version_name' => 'Darby Bible Translation',
                'version_code' => 'darby_bible_translation',
                'enabled'      => false,
                'enabled_to_compare'      => true,
            ],
            [
                'version_name' => 'English Revised Version',
                'version_code' => 'english_revised',
                'enabled'      => false,
                'enabled_to_compare'      => true,
            ],
            [
                'version_name' => 'Webster Bible Translation',
                'version_code' => 'webster_bible',
                'enabled'      => false,
                'enabled_to_compare'      => true,
            ],
            [
                'version_name' => 'World English Bible',
                'version_code' => 'world_english',
                'enabled'      => false,
                'enabled_to_compare'      => true,
            ],
            [
                'version_name' => 'Young\'s Literal Translation',
                'version_code' => 'youngs_literal',
                'enabled'      => false,
                'enabled_to_compare'      => true,
            ],
            [
                'version_name' => 'American King James Version',
                'version_code' => 'american_king_james',
                'enabled'      => false,
                'enabled_to_compare'      => true,
            ],

        ];
    }
}
