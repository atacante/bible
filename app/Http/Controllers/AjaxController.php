<?php

namespace App\Http\Controllers;

use App\BaseModel;
use App\BooksListEn;
use App\Helpers\ViewHelper;
use App\LexiconKjv;
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

    public function getVersesList()
    {
        $book = Request::input('book_id',Config::get('app.defaultBookNumber'));
        $chapter = Request::input('chapter',Config::get('app.defaultChapterNumber'));
        $verses = ViewHelper::prepareVersesForSelectBox(BaseModel::getVerses($book,$chapter));
        return response()->json($verses);
    }

    public function getPrintChapter()
    {
        $version = Request::input('version', Config::get('app.defaultBibleVersion'));
        $book = Request::input('book', Config::get('app.defaultBookNumber'));
        $chapter = Request::input('chapter', Config::get('app.defaultChapterNumber'));

        $versesModel = BaseModel::getVersesModelByVersionCode($version);
        $content['version_code'] = $version;
        $content['heading'] = BooksListEn::find($book)->book_name . " " . $chapter;
        $content['verses'] = $versesModel::query()->where('book_id', $book)->where('chapter_num', $chapter)->orderBy('verse_num')->get();

        return view('reader.printchapter', ['content' => $content]);
    }

    public function getLexiconInfo(){
        $definitionId = Request::input('definition_id');
        $lexiconinfo = LexiconKjv::query()->where('id',$definitionId)->first();
        return view('reader.lexiconinfo', ['lexiconinfo' => $lexiconinfo]);
    }
}
