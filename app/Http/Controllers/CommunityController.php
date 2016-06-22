<?php

namespace App\Http\Controllers;

use App\Journal;
use App\Note;
use App\Prayer;
//use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

class CommunityController extends Controller
{
    private $searchFilter;

    private function prepareFilters($model)
    {
        $this->searchFilter = Request::input('search', false);

        if (!empty($this->searchFilter)) {
            $model->where(function($ow) {
                $ow->orWhere('name', 'ilike', '%' . $this->searchFilter . '%');
                $ow->orWhere('email', 'ilike', '%' . $this->searchFilter . '%');
            });
        }

        return $model;
    }

    public function getWall()
    {
        $type = Request::input('type', 'all');

        $limit = 10;
        $page = Input::get('page',1);
        $offset = $limit*($page-1);

        $myFriends = [];
        if(Auth::user()){
            $myFriends = Auth::user()->friends->modelKeys();
        }

        $journalQuery = Journal::with(['verse','user'])
            ->selectRaw('id,user_id,verse_id,created_at,highlighted_text,journal_text as text,\'journal\' as type,bible_version,published_at')
//            ->where('user_id',Auth::user()?Auth::user()->id:null)
            ->where('access_level',Journal::ACCESS_PUBLIC_ALL);
        if(Auth::user() && $type == 'friends'){
            $journalQuery->whereIn('user_id',$myFriends);
            $journalQuery->where('user_id', '!=', Auth::user()->id);
        }
        $journalCount = $journalQuery->count();
        $prayersQuery = Prayer::with(['verse','user'])
            ->selectRaw('id,user_id,verse_id,created_at,highlighted_text,prayer_text as text,\'prayer\' as type,bible_version,published_at')
//            ->where('user_id',Auth::user()?Auth::user()->id:null)
            ->where('access_level',Journal::ACCESS_PUBLIC_ALL);
        if(Auth::user() && $type == 'friends'){
            $prayersQuery->whereIn('user_id',$myFriends);
            $prayersQuery->where('user_id', '!=', Auth::user()->id);
        }
        $prayersCount = $prayersQuery->count();
        $notesQuery = Note::with(['verse','user'])
            ->selectRaw('id,user_id,verse_id,created_at,highlighted_text,note_text as text,\'note\' as type,bible_version,published_at')
//            ->where('user_id',Auth::user()?Auth::user()->id:null)
            ->where('access_level',Journal::ACCESS_PUBLIC_ALL);
        if(Auth::user() && $type == 'friends'){
            $notesQuery->whereIn('user_id',$myFriends);
            $notesQuery->where('user_id', '!=', Auth::user()->id);
        }
        $notesCount = $notesQuery->count();

        $entriesQuery = $notesQuery->union($journalQuery)->union($prayersQuery);
        $entriesQuery->orderBy('published_at','desc')->orderBy('created_at','desc')->limit($limit)->offset($offset);

        $entries = $entriesQuery->get();
        $totalCount = $notesCount+$journalCount+$prayersCount;
        $entries = new LengthAwarePaginator(
            $entries,
            $totalCount,
            $limit,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(), //resolve the path
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
        $content['entries'] = $entries;
        $content['nextPage'] = ($limit*$page < $totalCount)?$page+1:false;
        $view = 'community.wall';
        if(Request::ajax()){
            $view = "community.wall-items";
        }
        return view($view, ['content' => $content]);
    }

    public function getJoin()
    {
        
    }

    public function getGroups()
    {
        return view('community.groups', []);
    }

    public function getFindFriends()
    {
        Session::put('back', Request::fullUrl());

        $type = Request::input('type', 'all');

        $users = User::whereHas('roles', function ($q) {
                  $q->whereIn('slug',[Config::get('app.role.user')]);
              });
        $users = $this->prepareFilters($users);

        $myFriends = [];
        if(Auth::user()){
            $myFriends = Auth::user()->friends->modelKeys();
        }

        switch($type){
            case "my":
                if(Auth::user()){
                    $users->whereIn('id',$myFriends);
                }
                break;
            case "new":
                if(Auth::user()){
                    $users->whereNotIn('id',$myFriends);
                }
                break;
        }

        if(Auth::user()){
            $users->where('id', '!=', Auth::user()->id);
        }

        $content['people'] = $users->orderBy('created_at','desc')->paginate(10);

        return view('community.find-friends', ['content' => $content,  'myFriends' => $myFriends]);
    }

    public function getBlog()
    {
        return view('community.blog', []);
    }
}
