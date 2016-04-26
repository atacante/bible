<?php

namespace App\Http\Controllers;

use App\BaseModel;
use App\BooksListEn;
use App\Helpers\ViewHelper;
use App\LexiconKjv;
use App\Note;
use App\VersesAmericanStandardEn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AjaxController extends Controller
{
    public function getBooksList()
    {
        $version = Request::input('version',false);
        if($version == 'berean'){
            $booksQuery = BooksListEn::where('id', '>', 39)->get();
        }
        else{
            $booksQuery = BooksListEn::all();
        }

        return response()->json(ViewHelper::prepareForSelectBox($booksQuery,'id','book_name'));
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
        $lexiconModel = BaseModel::getLexiconModelByVersionCode(Request::input('lexversion'));
        $lexiconinfo = $lexiconModel::query()->with('locations')->where('id',$definitionId)->first();
        $view = 'symbolism';
        if(Request::cookie('readerMode') == 'intermediate'){
            $view = 'lexiconinfo';
        }
        return view('reader.'.$view, ['lexiconinfo' => $lexiconinfo]);
    }

    public function getViewNote(){
        $id = Request::input('id');
        $note = Note::query()->where('user_id',Auth::user()->id)->find($id);
        return view('notes.view', ['model' => $note]);
    }

    public function getPrintNote(){
        $ids = (array)Request::input('id');
        $notes = Note::query()->where('user_id',Auth::user()->id)->whereIn('id',$ids)->orderBy('created_at','desc')->get();
        return view('notes.print', ['model' => $notes]);
    }
}
