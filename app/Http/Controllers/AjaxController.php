<?php

namespace App\Http\Controllers;

use App\BooksListEn;
use App\VersesAmericanStandardEn;
use Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AjaxController extends Controller
{
    public function getBooksList()
    {
        $versions = BooksListEn::all();
        return response()->json($versions);
    }
    public function getChaptersList()
    {
        $book = Request::input('book_id');
        $chapters = $this->prepareChaptersForSelectBox(VersesAmericanStandardEn::select(['chapter_num','book_id'])->distinct()->with('booksListEn')->where('book_id', $book)->orderBy('chapter_num')->get()->toArray());
        return response()->json($chapters);
    }
}
