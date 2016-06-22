<?php

namespace App\Http\Controllers;

use App\BaseModel;
use App\Helpers\ViewHelper;
use App\Journal;
use App\MyEntries;
use App\Note;
use App\Prayer;
use App\Tag;
use App\User;
//use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use \Illuminate\Support\Facades\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Kodeine\Acl\Models\Eloquent\Role;
use Krucas\Notification\Facades\Notification;
use Validator;


class UserController extends Controller
{
    protected $sortby;
    protected $order;

    private $searchFilter;
    private $dateFrom;
    private $dateTo;
    private $version;
    private $bookFilter;
    private $chapterFilter;
    private $verseFilter;
    private $tags;

    private static $myJourneyColumns = [
        "Type"=>false,
        "Text"=>"text",
        "Verse"=>"verse_id",
        "Relations v1"=>false,
        "Relations v2"=>false,
        "Tags"=>false,
        "Access"=>false,
        "Created"=>"created_at"
    ];

    private function prepareFilters($model,$type)
    {
        $this->searchFilter = Request::input('search', false);
        $this->dateFrom = Request::input('date_from', false);
        $this->dateTo = Request::input('date_to', false);
        $this->version = Request::input('version', false);
        $this->bookFilter = Request::input('book', false);
        $this->chapterFilter = Request::input('chapter', false);
        $this->verseFilter = Request::input('verse', false);
        $this->tags = Request::input('tags', []);

        if (!empty($this->searchFilter)) {
            $model->where($type.'_text', 'ilike', '%' . $this->searchFilter . '%');
        }

        if (!empty($this->dateFrom)) {
            $model->whereRaw('created_at >= to_timestamp(' . strtotime($this->dateFrom . " 00:00:00") . ")");
        }

        if (!empty($this->dateTo)) {
            $model->whereRaw('created_at <= to_timestamp(' . strtotime($this->dateTo . " 23:59:59") . ")");
        }

        if (!empty($this->version) && $this->version != 'all') {
            $model->where('bible_version', $this->version);
        }

        if (!empty($this->bookFilter)) {
            $model->whereHas('verse', function ($q) {
                $q->where('book_id', $this->bookFilter);
            });
        }

        if (!empty($this->chapterFilter)) {
            $model->whereHas('verse', function ($q) {
                $q->where('chapter_num', $this->chapterFilter);
            });
        }

        if (!empty($this->verseFilter)) {
            $model->whereHas('verse', function ($q) {
                $q->where('verse_num', $this->verseFilter);
            });
        }

        if (!empty($this->tags)) {
            $model->whereHas('tags', function ($q) {
                $q->where(function($ow) {
                    foreach ($this->tags as $tag) {
                        $ow->orWhere('tag_id', $tag);
                    }
                });
            });
        }
        return $model;
    }

    public function anyProfile(\Illuminate\Http\Request $request)
    {
        $user = User::find(Auth::user()->id);

        if($inputs = Input::old()){
            $user->fill($inputs);
        }

        if (Request::isMethod('put')) {
            $this->validate($request, $user->rules());
            if($user->update(Input::all())){
                Notification::successInstant('Your profile info successfully saved');
            }
        }

        return view('user.profile',
            [
                'model' => $user,
                'page_title' => 'My Profile',
            ]);
    }

    public function getMyJourney()
    {
        Session::flash('backUrl', Request::fullUrl());

        $limit = 10;
        $page = Input::get('page',1);
        $offset = $limit*($page-1);

        $this->sortby = Input::get('sortby', 'created_at');
        $this->order = Input::get('order', 'desc');

        $journalQuery = Journal::with(['verse.booksListEn','tags'])
            ->selectRaw('id,verse_id,note_id,null as journal_id,prayer_id,created_at,highlighted_text,journal_text as text,\'journal\' as type,rel_code,access_level,
            CASE WHEN journal.verse_id IS NOT NULL THEN (SELECT count(*) FROM notes WHERE journal.verse_id = notes.verse_id) ELSE (SELECT count(*) FROM notes WHERE journal.rel_code = notes.rel_code) END as notesCount,
            CASE WHEN journal.verse_id IS NOT NULL THEN (SELECT count(*) FROM journal as j WHERE j.verse_id = journal.verse_id) ELSE (SELECT count(*) FROM journal as j WHERE j.rel_code = journal.rel_code) END as journalCount,
            CASE WHEN journal.verse_id IS NOT NULL THEN (SELECT count(*) FROM prayers WHERE journal.verse_id = prayers.verse_id) ELSE (SELECT count(*) FROM prayers WHERE journal.rel_code = prayers.rel_code) END as prayersCount
            ')
            ->where('user_id',Auth::user()?Auth::user()->id:null)
            /*->whereHas('tags', function($q){
                $q->where('journal_tags.journal_id', '=', 53);
            })*/;
        $content['journalCount'] = $journalQuery->count();
        $journalQuery = $this->prepareFilters($journalQuery,'journal');
        $content['tags']['journal'] = $journalQuery->get()->pluck('tags','id');
        $content['journal']['notesCount'] = $journalQuery->get()->pluck('tags','id');
        $journalCount = $journalQuery->count();

        $prayersQuery = Prayer::with(['verse.booksListEn','tags'])
            ->selectRaw('id,verse_id,note_id,journal_id,null as prayer_id,created_at,highlighted_text,prayer_text as text,\'prayer\' as type,rel_code,access_level,
            CASE WHEN prayers.verse_id IS NOT NULL THEN (SELECT count(*) FROM notes WHERE prayers.verse_id = notes.verse_id) ELSE (SELECT count(*) FROM notes WHERE prayers.rel_code = notes.rel_code) END as notesCount,
            CASE WHEN prayers.verse_id IS NOT NULL THEN (SELECT count(*) FROM journal WHERE prayers.verse_id = journal.verse_id) ELSE (SELECT count(*) FROM journal WHERE prayers.rel_code = journal.rel_code) END as journalCount,
            CASE WHEN prayers.verse_id IS NOT NULL THEN (SELECT count(*) FROM prayers as p WHERE p.verse_id = prayers.verse_id) ELSE (SELECT count(*) FROM prayers as p WHERE p.rel_code = prayers.rel_code) END as prayersCount
            ')
            ->where('user_id',Auth::user()?Auth::user()->id:null);
        $content['prayersCount'] = $prayersQuery->count();
        $prayersQuery = $this->prepareFilters($prayersQuery,'prayer');
        $content['tags']['prayer'] = $prayersQuery->get()->pluck('tags','id');
        $prayersCount = $prayersQuery->count();

        $notesQuery = Note::with(['verse.booksListEn','tags'])
            ->selectRaw('id,verse_id,null as note_id,journal_id,prayer_id,created_at,highlighted_text,note_text as text,\'note\' as type,rel_code,access_level,
            CASE WHEN notes.verse_id IS NOT NULL THEN (SELECT count(*) FROM notes as n WHERE notes.verse_id = n.verse_id) ELSE (SELECT count(*) FROM notes as n WHERE notes.rel_code = n.rel_code) END as notesCount,
            CASE WHEN notes.verse_id IS NOT NULL THEN (SELECT count(*) FROM journal WHERE notes.verse_id = journal.verse_id) ELSE (SELECT count(*) FROM journal WHERE notes.rel_code = journal.rel_code) END as journalCount,
            CASE WHEN notes.verse_id IS NOT NULL THEN (SELECT count(*) FROM prayers WHERE notes.verse_id = prayers.verse_id) ELSE (SELECT count(*) FROM prayers WHERE notes.rel_code = prayers.rel_code) END as prayersCount
            ')
            ->where('user_id',Auth::user()?Auth::user()->id:null);
        $content['notesCount'] = $notesQuery->count();
        $notesQuery = $this->prepareFilters($notesQuery,'note');
        $content['tags']['note'] = $notesQuery->get()->pluck('tags','id');
        $notesCount = $notesQuery->count();

        $entryTypes = Request::get('types',false);
        $entriesQuery = false;
        if(!$entryTypes || count(array_intersect($entryTypes, ['note','journal','prayer'])) == 3){
            $entriesQuery = $notesQuery->union($journalQuery)->union($prayersQuery);
        }
        else{
            switch(true){
                case count(array_intersect($entryTypes, ['note','journal'])) == 2:
                    $entriesQuery = $notesQuery->union($journalQuery);
                    $prayersCount = 0;
                    break;
                case count(array_intersect($entryTypes, ['note','prayer'])) == 2:
                    $entriesQuery = $notesQuery->union($prayersQuery);
                    $journalCount = 0;
                    break;
                case count(array_intersect($entryTypes, ['journal','prayer'])) == 2:
                    $entriesQuery = $journalQuery->union($prayersQuery);
                    $notesCount = 0;
                    break;
                case in_array('note',$entryTypes):
                    $entriesQuery = $notesQuery;
                    $prayersCount = 0;
                    $journalCount = 0;
                    break;
                case in_array('journal',$entryTypes):
                    $entriesQuery = $journalQuery;
                    $prayersCount = 0;
                    $notesCount = 0;
                    break;
                case in_array('prayer',$entryTypes):
                    $entriesQuery = $prayersQuery;
                    $notesCount = 0;
                    $journalCount = 0;
                    break;
            }
        }
        $entriesQuery->orderBy($this->sortby, $this->order)->limit($limit)->offset($offset);

        $entries = $entriesQuery->get();
        $entries = new LengthAwarePaginator(
            $entries,
            $notesCount+$journalCount+$prayersCount,
            $limit,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(), //resolve the path
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        $content['entries'] = $entries;

        $content['action'] = 'user/my-journey';
        $content['columns'] = self::$myJourneyColumns;

        $content['sortby'] = $this->sortby;
        $content['order'] = $this->order;

        return view('user.my-journey', ['content' => $content]);
    }

    public function getFollowFriend($id)
    {
        $user = User::find($id);
        Auth::user()->followFriend($user);

        if(Request::ajax()){
            return 1;
        }

        return ($url = Session::pull('back'))
            ? Redirect::to($url)
            : Redirect::back();
    }

    public function getRemoveFriend($id)
    {
        $user = User::find($id);
        Auth::user()->removeFriend($user);

        if(Request::ajax()){
            return 1;
        }

        return Redirect::back();
    }

    public function anyUploadAvatar()
    {
        $userId = Request::get('user_id',Auth::user()->id);
        if (Input::hasFile('file')) {
            $file = Input::file('file');
            $filePath = Config::get('app.userAvatars').$userId.'/';
            if(!File::isDirectory(public_path() . $filePath)){
                File::makeDirectory(public_path() . $filePath, 0777, true);
            }
            $thumbPath = $filePath . 'thumbs/';
            $fileName = time() . '-' . $file->getClientOriginalName();
            $file = $file->move(public_path() . $filePath, $fileName);

//            File::makeDirectory(public_path() . $filePath, 0777, true);
            if(!File::isDirectory(public_path() . $thumbPath)){
                File::makeDirectory(public_path() . $thumbPath, 0777, true);
            }

            $thumbPath = public_path($thumbPath . $fileName);
            if($file){
                $user = User::find($userId);
                $user->avatar = $fileName;
                $user->save();
            }
            // Resizing 340x340
            Image::make($file->getRealPath())->fit(200, 200)->save($thumbPath)->destroy();
            return response()->json(array('filename'=> $fileName), 200);
        }
        return false;
    }

    public function anyDeleteAvatar($filename = false)
    {
        $userId = Request::get('user_id',Auth::user()->id);
        if(!$filename){
            $filename = Input::get('filename');
        }

        $user = User::query()->where('avatar', $filename)->where('id', $userId)->first();
        if($user){
            $user->avatar = null;
            if($user->save()){
                $filePath = public_path(Config::get('app.userAvatars').$userId.'/'.$filename);
                $thumbPath = public_path(Config::get('app.userAvatars').$userId.'/thumbs/'.$filename);
                File::delete($filePath);
                File::delete($thumbPath);
            }
        }
        return response()->json($user, 200);
    }
}
