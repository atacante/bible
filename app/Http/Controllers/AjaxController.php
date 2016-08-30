<?php

namespace App\Http\Controllers;

use App\BaseModel;
use App\BooksListEn;
use App\Helpers\ViewHelper;
use App\Journal;
use App\LexiconKjv;
use App\Location;
use App\Note;
use App\Prayer;
use App\User;
use App\VersesAmericanStandardEn;
use App\VersionsListEn;
use App\WallPost;
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
    public function getUsersList()
    {
        $term = Request::input('term',false);
        $users = User::where('name','ilike', '%' . $term . '%')
            ->whereHas('roles', function ($q) {
                $q->whereIn('slug',[Config::get('app.role.user')]);
            })
            ->pluck('name','id');
        $usersSelect = [];
        $users->each(function ($item, $key) use(&$usersSelect) {
            $usersSelect[] = ['id' => $key, 'text' => $item];
        });
        return response()->json($usersSelect);
    }
    public function getChaptersList()
    {
        $book = Request::input('book_id',Config::get('app.defaultBookNumber'));
        $chapters = ViewHelper::prepareChaptersForSelectBox(BaseModel::getChapters($book));
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
            $view = 'lexiconinfo_new';
        }
        return view('reader.'.$view, ['lexiconinfo' => $lexiconinfo]);
    }

    public function getViewNote(){
        $id = Request::input('id');
        $note = Note::query()->/*where('user_id',Auth::user()->id)->*/find($id);
        return view('notes.view', ['model' => $note]);
    }

    public function getPrintNote(){
        $ids = (array)Request::input('id');
        $notes = Note::query()->where('user_id',Auth::user()->id)->whereIn('id',$ids)->orderBy('created_at','desc')->get();
        return view('notes.print', ['model' => $notes]);
    }

    public function getPrintJournal(){
        $ids = (array)Request::input('id');
        $journal = Journal::query()->where('user_id',Auth::user()->id)->whereIn('id',$ids)->orderBy('created_at','desc')->get();
        return view('journal.print', ['model' => $journal]);
    }

    public function getPrintPrayer(){
        $ids = (array)Request::input('id');
        $prayers = Prayer::query()->where('user_id',Auth::user()->id)->whereIn('id',$ids)->orderBy('created_at','desc')->get();
        return view('prayers.print', ['model' => $prayers]);
    }

    public function getViewJournal(){
        $id = Request::input('id');
        $journal = Journal::query()->/*where('user_id',Auth::user()->id)->*/find($id);
        return view('journal.view', ['model' => $journal]);
    }

    public function getViewPrayer(){
        $id = Request::input('id');
        $prayer = Prayer::query()->/*where('user_id',Auth::user()->id)->*/find($id);
        return view('prayers.view', ['model' => $prayer]);
    }

    public function getViewStatus(){
        $id = Request::input('id');
        $status = WallPost::query()->/*where('user_id',Auth::user()->id)->*/find($id);
        return view('wall-posts.view', ['model' => $status]);
    }

    public function getLocationMapEmbedCode(){
        $id = Request::input('id');
        $location = Location::find($id);
        if($location){
            return $location->g_map;
        }
        return 0;
    }

    public function anyUpdateVersion()
    {
        $version = Request::input('version_code');
        $versionModel = VersionsListEn::where('version_code', $version);
        $versionModel->update(Request::input());
    }
}
