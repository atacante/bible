<?php

namespace App\Http\Controllers;

use App\BooksListEng;
use App\VersesAmericanStandardEng;
use Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AjaxController extends Controller
{
    public function getBooksList()
    {
        $versions = BooksListEng::all();
        return response()->json($versions);
    }
    public function getChaptersList()
    {
        $book = Request::input('book_id');
        $chapters = $this->prepareChaptersForSelectBox(VersesAmericanStandardEng::select(['chapter_num','book_id'])->distinct()->with('booksListEng')->where('book_id', $book)->orderBy('chapter_num')->get()->toArray());
        return response()->json($chapters);
    }
}
