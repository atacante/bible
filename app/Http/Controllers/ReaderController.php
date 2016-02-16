<?php

namespace App\Http\Controllers;

use App\BooksListEng;
use App\VersesAmericanStandardEng;
use App\VersionsListEng;
use Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReaderController extends Controller
{
    public function getRead()
    {
        $version = Request::input('version','american_standard_version');
        $book = Request::input('book',1);
        $chapter = Request::input('chapter',1);

        $filters['versions'] = $this->prepareForSelectBox(VersionsListEng::all()->toArray(),'version_code','version_name');
        $filters['books'] = $this->prepareForSelectBox(BooksListEng::all()->toArray(),'id','book_name');
        $filters['chapters'] = $this->prepareChaptersForSelectBox(VersesAmericanStandardEng::select(['chapter_num','book_id'])->distinct()->with('booksListEng')->where('book_id', $book)->orderBy('chapter_num')->get()->toArray());

        $content['heading'] = BooksListEng::find($book)->book_name." ".$chapter;
        $content['verses'] = VersesAmericanStandardEng::query()->where('book_id', $book)->where('chapter_num',$chapter)->orderBy('verse_num')->get();

        $content['pagination'] = $this->chaptersPagination($chapter,$filters['chapters'],$book,$filters['books']);

        return view('reader.main',['filters' => $filters,'content' => $content]);
    }

    private function chaptersPagination($currentChapter,$allChapters,$currentBook,$allBooks)
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

        return ['chapterPrev' => $prevChapter,'chapterNext' => $nextChapter,'prevBook' => $prevBook,'nextBook' => $nextBook];
    }
}
