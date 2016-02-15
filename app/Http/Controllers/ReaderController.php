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

        return view('reader.main',['filters' => $filters,'content' => $content]);
    }
}
