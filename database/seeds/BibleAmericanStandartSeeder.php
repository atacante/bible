<?php

use App\BooksListEng;
use App\VersesAmericanStandardEng;
use App\VersionsListEng;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BibleAmericanStandartSeeder extends Seeder
{
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
            DB::statement("TRUNCATE TABLE verses_american_standard_eng");
            DB::statement("TRUNCATE TABLE versions_list_eng");
            DB::statement("TRUNCATE TABLE books_list_eng CASCADE");
            VersionsListEng::create(['version_name' => 'American Standard Version','version_code' => str_replace(' ','_',strtolower('American Standard Version'))]);
            $prevBook = '';
            $bookId = 0;
            foreach($csv->data as $row){
                $book = explode(':',trim($row['Verse']));
                $bookAndChapter = explode(' ',$book[0]);
                $chapter = array_pop($bookAndChapter);
                $verse_num = $book[1];
                $book = implode(' ',$bookAndChapter);//array_pop() result
                $verse = $row['American Standard Version'];
                if($book != $prevBook){
                    $bookModel = BooksListEng::create(['id' => $bookId+1,'book_name' => $book]);
                    $prevBook = $book;
                    $bookId = $bookModel->id;
                }



                $verse = [
                    'book_id' => $bookId,
                    'chapter_num' => $chapter,
                    'verse_num' => $verse_num,
                    'verse_text' => $verse
                ];
                VersesAmericanStandardEng::insert($verse);
//                VersesAmericanStandardEng::create($verse);
            }
        }
    }
}
