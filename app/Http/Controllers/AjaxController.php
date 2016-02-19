<?php

namespace App\Http\Controllers;

use App\BaseModel;
use App\BooksListEn;
use App\VersesAmericanStandardEn;
use Illuminate\Support\Facades\Config;
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
        $book = Request::input('book_id',Config::get('app.defaultBookNumber'));
        $chapters = $this->prepareChaptersForSelectBox(BaseModel::getChapters($book));
        return response()->json($chapters);

        /*
        Use this code if need to get chapters for specific Bible version

        $version = Request::input('version', Config::get('app.defaultBibleVersion'));
        $book = Request::input('book_id',Config::get('app.defaultBookNumber'));
        $chapters = $this->prepareChaptersForSelectBox(BaseModel::getChapters($book,$version));
        return response()->json($chapters);

        */
    }
}
