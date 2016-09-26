<?php

namespace App\Http\Controllers;

use App\ContentReport;
use App\Journal;
use App\Note;
use App\Prayer;
//use Illuminate\Http\Request;

use App\Http\Requests;
use App\User;
use App\WallPost;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Krucas\Notification\Facades\Notification;

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
        Session::flash('backUrl', Request::fullUrl());

        $type = Request::input('type', 'all');

        $limit = 10;
        $page = Input::get('page',1);
        $offset = $limit*($page-1);

        $myFriends = [];
        if(Auth::user()){
            $myFriends = Auth::user()->friends->modelKeys();
        }

        $statusesQuery = WallPost::with(['user','images'])
            ->selectRaw('id,user_id,verse_id,created_at,updated_at,null as highlighted_text,status_text as text,type,null as bible_version,published_at,access_level,
                (SELECT count(*) FROM wall_likes WHERE item_type = \'App\WallPost\' AND item_id = wall_posts.id) as likesCount,
                (SELECT count(*) FROM wall_comments WHERE type = \'App\WallPost\' AND item_id = wall_posts.id) as commentsCount
            ')
            ->where(function($q) {
                $q->whereIn('access_level',[WallPost::ACCESS_PUBLIC_ALL]);
                if(Auth::user()){
                    $q->orWhere(function($sq) {
                        $sq->whereIn('access_level',[WallPost::ACCESS_PRIVATE]);
                        $sq->where('user_id',Auth::user()->id);
                    });
                    $q->orWhere(function($sq) {
                        $sq->whereIn('access_level',[WallPost::ACCESS_PUBLIC_FRIENDS]);
                        $sq->whereIn('user_id',array_merge(Auth::user()->friends->modelKeys(),Auth::user()->requests->modelKeys(),[Auth::user()->id]));
                    });
                }
            })
            ->where('wall_type',WallPost::WALL_TYPE_PUBLIC);
        if(Auth::user() && $type == 'friends'){
            $statusesQuery->whereIn('user_id',$myFriends);
            $statusesQuery->where('user_id', '!=', Auth::user()->id);
        }
        $content['wall-posts']['images'] = $statusesQuery->get()->pluck('images','id');
        $statusesCount = $statusesQuery->count();
        $lastIds['status'] = (int) $statusesQuery->max('id');

        $journalQuery = Journal::with(['verse','user','images'])
            ->selectRaw('id,user_id,verse_id,created_at,updated_at,highlighted_text,journal_text as text,\'journal\' as type,bible_version,published_at,access_level,
                (SELECT count(*) FROM wall_likes WHERE item_type = \'App\Journal\' AND item_id = journal.id) as likesCount,
                (SELECT count(*) FROM wall_comments WHERE type = \'App\Journal\' AND item_id = journal.id) as commentsCount
            ')
//            ->where('user_id',Auth::user()?Auth::user()->id:null)
            ->where('access_level',Journal::ACCESS_PUBLIC_ALL);
        if(Auth::user() && $type == 'friends'){
            $journalQuery->whereIn('user_id',$myFriends);
            $journalQuery->where('user_id', '!=', Auth::user()->id);
        }
        $content['journal']['images'] = $journalQuery->get()->pluck('images','id');
        $journalCount = $journalQuery->count();
        $lastIds['journal'] = (int) $journalQuery->max('id');

        $prayersQuery = Prayer::with(['verse','user','images'])
            ->selectRaw('id,user_id,verse_id,created_at,updated_at,highlighted_text,prayer_text as text,\'prayer\' as type,bible_version,published_at,access_level,
                (SELECT count(*) FROM wall_likes WHERE item_type = \'App\Prayer\' AND item_id = prayers.id) as likesCount,
                (SELECT count(*) FROM wall_comments WHERE type = \'App\Prayer\' AND item_id = prayers.id) as commentsCount
            ')
//            ->where('user_id',Auth::user()?Auth::user()->id:null)
            ->where('access_level',Journal::ACCESS_PUBLIC_ALL);
        if(Auth::user() && $type == 'friends'){
            $prayersQuery->whereIn('user_id',$myFriends);
            $prayersQuery->where('user_id', '!=', Auth::user()->id);
        }
        $content['prayers']['images'] = $prayersQuery->get()->pluck('images','id');
        $prayersCount = $prayersQuery->count();
        $lastIds['prayer'] = (int) $prayersQuery->max('id');

        $notesQuery = Note::with(['verse','user','images'])
            ->selectRaw('id,user_id,verse_id,created_at,updated_at,highlighted_text,note_text as text,\'note\' as type,bible_version,published_at,access_level,
                (SELECT count(*) FROM wall_likes WHERE item_type = \'App\Note\' AND item_id = notes.id) as likesCount,
                (SELECT count(*) FROM wall_comments WHERE type = \'App\Note\' AND item_id = notes.id) as commentsCount
            ')
//            ->where('user_id',Auth::user()?Auth::user()->id:null)
            ->where('access_level',Journal::ACCESS_PUBLIC_ALL);
        if(Auth::user() && $type == 'friends'){
            $notesQuery->whereIn('user_id',$myFriends);
            $notesQuery->where('user_id', '!=', Auth::user()->id);
        }
        $content['notes']['images'] = $notesQuery->get()->pluck('images','id');
        $notesCount = $notesQuery->count();
        $lastIds['note'] = (int) $notesQuery->max('id');

        if(Request::ajax() && Request::input('checkPosts', null)) {
            $lastNoteId = Request::input('lastNoteId', 0);
            $newNotesCount = $notesQuery->where('id', '>', $lastNoteId)->count();
        }

        $entriesQuery = $notesQuery->union($journalQuery)->union($prayersQuery)->union($statusesQuery);
        $entriesQuery->orderBy('published_at','desc')->orderBy('created_at','desc')->limit($limit)->offset($offset);

        $entries = $entriesQuery->get();

        $totalCount = $notesCount+$journalCount+$prayersCount+$statusesCount;

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

            if(Request::input('checkPosts', null)){
                $lastStatusId = Request::input('lastStatusId', 0);
                $newStatusesCount = $statusesQuery->where('id','>',$lastStatusId)->count();

                $lastJournalId = Request::input('lastJournalId', 0);
                $newJournalCount = $journalQuery->where('id','>',$lastJournalId)->count();

                $lastPrayerId = Request::input('lastPrayerId', 0);
                $newPrayersCount = $prayersQuery->where('id','>',$lastPrayerId)->count();

                $newTotalCount = $newNotesCount+$newJournalCount+$newPrayersCount+$newStatusesCount;

                return $newTotalCount;
            }else{
                $view = "community.wall-items";
            }
        }
        $status = new WallPost();
        return view($view, ['content' => $content,'status'=>$status, 'lastIds' => $lastIds]);
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

        $limit = 10;
        $page = Input::get('page',1);
        $offset = $limit*($page-1);

        $users = User::whereHas('roles', function ($q) {
                  $q->whereIn('slug',[Config::get('app.role.user')]);
              });
        $users = $this->prepareFilters($users);

        $requests = [];
        $ignoredRequests = [];
        $myRequests = [];
        $myFriends = [];
        if(Auth::user()){
            $requests = Auth::user()->requests->modelKeys();
            $ignoredRequests = Auth::user()->requests()->where('ignore',true)->get()->modelKeys();
            $myRequests = Auth::user()->friends->modelKeys();
            $myFriends = array_intersect($requests, $myRequests);
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
            case "inbox-requests":
                if(Auth::user()){
                    $users->whereNotIn('id',$myFriends);
                    $users->whereNotIn('id',$ignoredRequests);
                    $users->whereIn('id',$requests);
                }
                break;
            case "sent-requests":
                if(Auth::user()){
                    $users->whereNotIn('id',$myFriends);
                    $users->whereIn('id',$myRequests);
                }
                break;
        }

        if(Auth::user()){
            $users->where('id', '!=', Auth::user()->id);
        }

        $totalCount = $users->count();

        $users->orderBy('created_at','desc')->limit($limit)->offset($offset);

        $content['people'] = $users->get();
        $content['people'] = new LengthAwarePaginator(
            $content['people'],
            $totalCount,
            $limit,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(), //resolve the path
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
        $content['nextPage'] = ($limit*$page < $totalCount)?$page+1:false;
        $view = 'community.find-friends';
        if(Request::ajax()){
            $view = "community.friend-items";
        }
        return view($view, [
            'content' => $content,
            'myFriends' => $myFriends,
            'requests' => $requests,
            'ignoredRequests' => $ignoredRequests,
            'myRequests' => $myRequests
        ]);
    }

    public function anyReport(\Illuminate\Http\Request $request,$type,$id)
    {
        switch($type){
            case 'note':
                $model = Note::find($id);
                break;
            case 'journal':
                $model = Journal::find($id);
                break;
            case 'prayer':
                $model = Prayer::find($id);
                break;
            case 'status':
                $model = WallPost::find($id);
                break;
        }

        if (!$model) {
            abort(404);
        }

        if (Request::isMethod('post')){
            $reportModel = new ContentReport();
            $text = Input::get('reason_text');
            $data = ['user_id' => Auth::user()->id,'reason_text' => $text];
            $this->validate($request, $reportModel->rules());

            $reportCreated = $model->contentReports()->create($data);
            if ($reportCreated) {
                Mail::queue([],[], function($message) use($data,$type)
                {
                    $admins = User::whereHas('roles', function ($q) {
                            $q->whereIn('slug',[Config::get('app.role.admin')]);
                        })->get();
                    foreach ($admins as $admin) {
                        $message
                            ->to($admin->email)
                            ->subject('Content has been reported')
                            ->setBody(view('emails.content_report', ['type' => $type,'reason' => $data['reason_text']])->render(), 'text/html');
                    }
                });
                return 1;
            }
            return 0;
        }

        $content['type'] = $type;
        $content['id'] = $id;

        return view('community.report-form', ['content' => $content]);
    }

    public function anyInvitePeople(\Illuminate\Http\Request $request)
    {
        $data = [
            'name' => 'friend',
            'inviterName' => Auth::user()->name,
            'inviterId' => Auth::user()->id,
            'invite_url' => url('invite/'.Auth::user()->id),
            'invite_link' => '<a href="'.url('invite/'.Auth::user()->id).'" class="">www.biblestudycompany.com</a>',
        ];

        if (Request::isMethod('post')){
            $emails = Input::get('emails',false);
            $invite_text = Input::get('invite_text',false);
            $rures = [
                'emails' => 'required',
                'invite_text' => 'required',
            ];
            $this->validate($request, $rures);
            
            $data['invite_text'] = str_replace('{invite_url}',$data['invite_url'],$invite_text);
            $data['invite_text'] = str_replace('{invite_link}',$data['invite_link'],$data['invite_text']);

            foreach ($emails as $email) {
                Mail::send([],[], function($message) use($email,$data)
                {
                    $message
                        ->to($email)
                        ->subject($data['inviterName'].' has invited you to join Bible Study Company')
                        ->setBody($data['invite_text'], 'text/html');
                });
            }
            Notification::successInstant('Invites have been successfully sent');
        }
        $data['invite_text'] = view('emails.invite', [])->render();
        return view('community.invite-people', ['content' => $data]);
    }
}
