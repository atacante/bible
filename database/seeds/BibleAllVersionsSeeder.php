<?php

use App\BaseModel;
use App\BooksListEn;
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
            $versions = VersionsListEn::versionsList();
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
            DB::statement("TRUNCATE TABLE versions_list_en");
            DB::statement("TRUNCATE TABLE books_list_en CASCADE");

            DB::statement("ALTER SEQUENCE versions_list_en_id_seq RESTART WITH 1");
            DB::statement("ALTER SEQUENCE books_list_en_id_seq RESTART WITH 1");

            VersionsListEn::create(['version_name' => 'American Standard Version','version_code' => str_replace(' ','_',strtolower('American Standard Version'))]);
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
                }
            }
        }
    }
}
