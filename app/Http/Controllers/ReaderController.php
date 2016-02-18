<?php

namespace App\Http\Controllers;

use App\BaseModel;
use App\BooksListEn;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use Illuminate\Support\Facades\Config;
use Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReaderController extends Controller
{
    public function getRead()
    {
        $version = Request::input('version','american_standard');
        $book = Request::input('book',1);
        $chapter = Request::input('chapter',1);

        $locale = Config::get('app.locale');// temporary static variable
        $versesModel = BaseModel::getModelByTableName('verses_'.$version.'_'.$locale);

        $filters['versions'] = $this->prepareForSelectBox(VersionsListEn::versionsList(),'version_code','version_name');
        $filters['books'] = $this->prepareForSelectBox(BooksListEn::all()->toArray(),'id','book_name');
        $filters['chapters'] = $this->prepareChaptersForSelectBox($versesModel::select(['chapter_num','book_id'])->distinct()->with('booksListEn')->where('book_id', $book)->orderBy('chapter_num')->get()->toArray());

        $content['heading'] = BooksListEn::find($book)->book_name." ".$chapter;
        $content['verses'] = $versesModel::query()->where('book_id', $book)->where('chapter_num',$chapter)->orderBy('verse_num')->get();

        $content['pagination'] = $this->pagination($chapter,$filters['chapters'],$book,$filters['books']);

        return view('reader.main',['filters' => $filters,'content' => $content]);
    }

    private function pagination($currentChapter,$allChapters,$currentBook,$allBooks)
    {
        $params = Request::input();

        /* Chapters links */
        $prevChapter = $currentChapter-1;

        if($prevChapter == 0){
            $prevChapter = false;
        }
        else{
            $params['chapter'] = $prevChapter;
            $prevChapter = $params;
        }

        $nextChapter = $currentChapter+1;
        if(!array_key_exists($nextChapter,$allChapters)){
            $nextChapter = false;
        }
        else{
            $params['chapter'] = $nextChapter;
            $nextChapter = $params;
        }

        /* Books links */
        $prevBook = $currentBook-1;

        if($prevBook == 0){
            $prevBook = false;
        }
        else{
            $params['book'] = $prevBook;
            $params['chapter'] = 1;
            $prevBook = $params;
        }

        $nextBook = $currentBook+1;
        if(!array_key_exists($nextBook,$allBooks)){
            $nextBook = false;
        }
        else{
            $params['book'] = $nextBook;
            $params['chapter'] = 1;
            $nextBook = $params;
        }

        return ['chapterPrev' => $prevChapter,'chapterNext' => $nextChapter,'bookPrev' => $prevBook,'bookNext' => $nextBook];
    }
}
