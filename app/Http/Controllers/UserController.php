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
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use \Illuminate\Support\Facades\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
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
//        "Relations"=>false,
        "Tags"=>false,
        "Accessibility"=>false,
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
            ->selectRaw('id,verse_id,created_at,highlighted_text,journal_text as text,\'journal\' as type,rel_code,access_level')
            ->where('user_id',Auth::user()?Auth::user()->id:null)
            /*->whereHas('tags', function($q){
                $q->where('journal_tags.journal_id', '=', 53);
            })*/;
        $content['journalCount'] = $journalQuery->count();
        $journalQuery = $this->prepareFilters($journalQuery,'journal');
        $content['tags']['journal'] = $journalQuery->get()->pluck('tags','id');
        $journalCount = $journalQuery->count();

        $prayersQuery = Prayer::with(['verse.booksListEn','tags'])
            ->selectRaw('id,verse_id,created_at,highlighted_text,prayer_text as text,\'prayer\' as type,rel_code,access_level')
            ->where('user_id',Auth::user()?Auth::user()->id:null);
        $content['prayersCount'] = $prayersQuery->count();
        $prayersQuery = $this->prepareFilters($prayersQuery,'prayer');
        $content['tags']['prayer'] = $prayersQuery->get()->pluck('tags','id');
        $prayersCount = $prayersQuery->count();

        $notesQuery = Note::with(['verse.booksListEn','tags'])
            ->selectRaw('id,verse_id,created_at,highlighted_text,note_text as text,\'note\' as type,rel_code,access_level')
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
}
