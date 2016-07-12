<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use App\Group;
use App\Http\Requests;

use App\Journal;
use App\Note;
use App\Prayer;
use App\User;
use App\WallPost;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Krucas\Notification\Facades\Notification;

class GroupsController extends Controller
{
    public function getIndex()
    {
        $content['groups'] = $this->getAllGroups();

        $content['myGroups'] = [];
        $content['joinedGroups'] = [];
        $content['groupsRequested'] = [];
        if(Auth::user()){
            $content['myGroups'] = $this->getMyGroups();
            $content['joinedGroups'] = $this->getJoinedGroups();
            $content['groupsRequested'] = $this->getGroupsRequested();
        }

        $content['joinedGroupsKeys'] = [];
        if(Auth::user()){
            $content['joinedGroupsKeys'] = Auth::user()->joinedGroups->modelKeys();
        }
        $content['nextPage'] = false;

        return view('groups.list', ['content' => $content]);
    }

    public function getAllGroups(){
        $limit = 6;
        $page = Input::get('page',1);
        $offset = $limit*($page-1);

        $groups = Group::query()->with('members');
        $totalCount = $groups->count();

        $content['items'] = $groups->orderBy('created_at','desc')->limit($limit)->offset($offset);

        $content['items'] = new LengthAwarePaginator(
            $content['items']->get(),
            $totalCount,
            $limit,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(), //resolve the path
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
        $content['nextPage'] = ($limit*$page < $totalCount)?$page+1:false;
        $data['groups'] = $content;
        $data['joinedGroupsKeys'] = [];
        if(Auth::user()){
            $data['joinedGroupsKeys'] = Auth::user()->joinedGroups->modelKeys();
        }
        if(Request::ajax()){
            return view('groups.items', ['dataKey' => 'groups','content' => $data]);
        }
        return $content;
    }

    public function getMyGroups(){
        $limit = 5;
        $page = Input::get('page',1);
        $offset = $limit*($page-1);

        $groups = Auth::user()->myGroups()->with('members');
        $totalCount = $groups->count();

        $content['items'] = $groups->orderBy('created_at','desc')->limit($limit)->offset($offset);

        $content['items'] = new LengthAwarePaginator(
            $content['items']->get(),
            $totalCount,
            $limit,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(), //resolve the path
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
        $content['nextPage'] = ($limit*$page < $totalCount)?$page+1:false;
        $data['myGroups'] = $content;
        $data['joinedGroupsKeys'] = [];
        if(Auth::user()){
            $data['joinedGroupsKeys'] = Auth::user()->joinedGroups->modelKeys();
        }
        if(Request::ajax()){
            return view('groups.items', ['dataKey' => 'myGroups','content' => $data]);
        }
        return $content;
    }

    public function getJoinedGroups(){
        $limit = 5;
        $page = Input::get('page',1);
        $offset = $limit*($page-1);

        $groups = Auth::user()->joinedGroups()->with('members');
        $totalCount = $groups->count();

        $content['items'] = $groups->orderBy('created_at','desc')->limit($limit)->offset($offset);

        $content['items'] = new LengthAwarePaginator(
            $content['items']->get(),
            $totalCount,
            $limit,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(), //resolve the path
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
        $content['nextPage'] = ($limit*$page < $totalCount)?$page+1:false;
        $data['joinedGroups'] = $content;
        $data['joinedGroupsKeys'] = [];
        if(Auth::user()){
            $data['joinedGroupsKeys'] = Auth::user()->joinedGroups->modelKeys();
        }

        if(Request::ajax()){
            return view('groups.items', ['dataKey' => 'joinedGroups','content' => $data]);
        }
        return $content;
    }

    public function anyCreate(\Illuminate\Http\Request $request)
    {
        if (!Auth::user() || !Auth::user()->is('user') || !Auth::user()->isPremium()) {
            abort(401);
        }
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }

        $model = new Group();

        if (Request::isMethod('post')) {
            $this->validate($request, $model->rules());
            $data = Input::all();
            $data['owner_id'] = Auth::user()->id;
            if ($model = $model->create($data)) {
                $this->anyUploadImage($model->id);
                Notification::success('Group has been successfully created');
            }
            if (!Request::ajax()) {
                return ($url = Session::get('backUrl')) ? Redirect::to($url) : Redirect::to('/groups?type=my');
            }
            else{
                return 1;
            }
        }
        $view = 'groups.create';
        if (Request::ajax()) {
            $view = 'groups.form';
        }
        return view($view, ['model' => $model]);
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = Group::query()->where('owner_id', Auth::user()->id)->find($id);
        if (!$model) {
            abort(404);
        }
        if (Request::isMethod('put')) {
            $this->validate($request, $model->rules());
            if ($model->update(Input::all())) {
                $this->anyUploadImage($model->id);
                Notification::success('Group has been successfully updated');
            }
            if (!Request::ajax()) {
                return ($url = Session::get('backUrl')) ? Redirect::to($url) : Redirect::to('/groups?type=my');
            }
            else{
                return 1;
            }
        }

        $view = 'groups.create';
        if (Request::ajax()) {
            $view = 'groups.form';
        }
        return view($view, ['model' => $model]);
    }

    public function getView($id)
    {
        Session::flash('backUrl', Request::fullUrl());

        $model = Group::query()->find($id);

        if (!$model) {
            return Redirect::to('/groups?type=my');
        }

        $limit = 10;
        $page = Input::get('page',1);
        $offset = $limit*($page-1);

        $content['joinedGroupsKeys'] = [];
        if(Auth::user()){
            $content['joinedGroupsKeys'] = Auth::user()->joinedGroups->modelKeys();
        }

        $content['membersSample'] = $this->getMembers($id,'random')['members'];
        $content['membersToRequest'] = $this->getMembersToRequest($id);

        if(Input::get('p') == 'members'){
            $membersData = $this->getMembers($id);
            $content['members'] = $membersData['members'];
            $content['nextPage'] = $membersData['nextPage'];
        }
        elseif(Input::get('p') == 'requests'){
            $requestsData = $this->getRequests($id);
            $content['requests'] = $requestsData['requests'];
            $content['nextPage'] = $requestsData['nextPage'];
        }
        else{
            $statusesQuery = WallPost::with(['verse','user'])
                ->selectRaw('id,user_id,verse_id,created_at,null as highlighted_text,text,type,null as bible_version,published_at')
                ->where(function($q) {
                    $q->whereIn('access_level',[WallPost::ACCESS_PUBLIC_ALL]);
                    if(Auth::user()){
                        $q->orWhere(function($sq) {
                            $sq->whereIn('access_level',[WallPost::ACCESS_PRIVATE]);
                            $sq->where('user_id',Auth::user()->id);
                        });
                        $q->orWhere(function($sq) {
                            $sq->whereIn('access_level',[WallPost::ACCESS_PUBLIC_FRIENDS]);
                            $sq->whereIn('user_id',array_merge(Auth::user()->friends->modelKeys(),Auth::user()->followers->modelKeys(),[Auth::user()->id]));
                        });
                    }
                })
                ->where('rel_id',$model->id);
            $statusesCount = $statusesQuery->count();
            $journalQuery = Journal::with(['verse','user'])
                ->selectRaw('id,user_id,verse_id,created_at,highlighted_text,journal_text as text,\'journal\' as type,bible_version,published_at')
                ->whereIn('access_level',[Journal::ACCESS_PUBLIC_GROUPS,Journal::ACCESS_SPECIFIC_GROUPS])
                ->whereIn('id',$model->journals->modelKeys());
            $journalCount = $journalQuery->count();
            $prayersQuery = Prayer::with(['verse','user'])
                ->selectRaw('id,user_id,verse_id,created_at,highlighted_text,prayer_text as text,\'prayer\' as type,bible_version,published_at')
                ->whereIn('access_level',[Journal::ACCESS_PUBLIC_GROUPS,Journal::ACCESS_SPECIFIC_GROUPS])
                ->whereIn('id',$model->prayers->modelKeys());
            $prayersCount = $prayersQuery->count();
            $notesQuery = Note::with(['verse','user'])
                ->selectRaw('id,user_id,verse_id,created_at,highlighted_text,note_text as text,\'note\' as type,bible_version,published_at')
                ->whereIn('access_level',[Journal::ACCESS_PUBLIC_GROUPS,Journal::ACCESS_SPECIFIC_GROUPS])
                ->whereIn('id',$model->notes->modelKeys());
            $notesCount = $notesQuery->count();

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
        }
        $status = new WallPost();
        return view('groups.view', ['model' => $model,'status' => $status,'content' => $content]);
    }

    public function getMembers($groupId,$order = false)
    {
        $type = Input::get('type','active');
        $limit = 10;
        $page = Input::get('page',1);
        $offset = $limit*($page-1);

        $group = Group::find($groupId);
        $members = User::join('groups_users','users.id', '=', 'groups_users.user_id')
            ->addSelect(DB::raw('users.*,(select banned from groups_users where groups_users.user_id = users.id and groups_users.group_id = '.$group->id.') as banned'))
            ->whereHas($type == 'banned'?'groupsBanned':'joinedGroups', function ($q) use ($group,$type)
                {
                    $q->where('group_id', $group->id);
                });
        if($type != 'banned'){
            $members->orWhere('users.id',$group->owner_id);
        }

        $members->groupBy('users.id')->groupBy('groups_users.user_id')/*->groupBy('groups_users.banned')*/->limit($limit)->offset($offset);

        $totalCount = $members->count();

        if($order == 'random'){
            $members->orderByRaw("RANDOM()");
        }
        else{
            $members->orderByRaw('CASE WHEN users.id='.$group->owner_id.' THEN 0 ELSE MAX(groups_users.id) END desc');
        }

        $content['members'] = $members->get();
        $content['members'] = new LengthAwarePaginator(
            $content['members'],
            $totalCount,
            $limit,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(), //resolve the path
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
        $content['nextPage'] = ($limit*$page < $totalCount)?$page+1:false;

        if(Request::ajax()){
            return view('groups.members', ['model' => $group,'content' => $content]);
        }

        return $content;
    }

    public function getRequests($groupId)
    {
        $limit = 10;
        $page = Input::get('page',1);
        $offset = $limit*($page-1);

        $group = Group::find($groupId);
        $requests = User::
//            join('groups_users','users.id', '=', 'groups_users.user_id')
//            ->addSelect(DB::raw('users.*'))
            whereHas('groupsRequests', function ($q) use ($group)
        {
            $q->where('connect_requests_id', $group->id);
        });

        $totalCount = $requests->count();

        $requests/*->groupBy('users.id')->groupBy('groups_users.user_id')*/->limit($limit)->offset($offset);

//        if($order == 'random'){
//            $members->orderByRaw("RANDOM()");
//        }
//        else{
//            $members->orderByRaw('MAX(groups_users.id) desc');
//        }

        $content['requests'] = $requests->get();
        $content['requests'] = new LengthAwarePaginator(
            $content['requests'],
            $totalCount,
            $limit,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(), //resolve the path
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
        $content['nextPage'] = ($limit*$page < $totalCount)?$page+1:false;

        if(Request::ajax()){
            return view('groups.requests', ['model' => $group,'content' => $content]);
        }

        return $content;
    }

    public function getGroupsRequested()
    {
        $limit = 5;
        $page = Input::get('page',1);
        $offset = $limit*($page-1);

        $groups = Auth::user()->groupsRequests()->with('members');
        $totalCount = $groups->count();

        $content['items'] = $groups->orderBy('created_at','desc')->limit($limit)->offset($offset);

        $content['items'] = new LengthAwarePaginator(
            $content['items']->get(),
            $totalCount,
            $limit,
            \Illuminate\Pagination\Paginator::resolveCurrentPage(), //resolve the path
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );
        $content['nextPage'] = ($limit*$page < $totalCount)?$page+1:false;
        $data['groupsRequested'] = $content;

        if(Request::ajax()){
            return view('groups.items', ['dataKey' => 'groupsRequested','content' => $data]);
        }
        return $content;
    }

    public function getMembersToRequest($groupId)
    {
        $group = Group::find($groupId);
        $groupUsers = $group->members->modelKeys();
        $groupRequests = $group->joinRequests()->pluck('user_id')->toArray();
        $users = User::
            whereNotIn('users.id',array_merge([$group->owner_id],$groupUsers,$groupRequests))
            ->where('users.plan_type',User::PLAN_PREMIUM);
        return $users->pluck('name','id')->toArray();
    }

    public function anyDelete($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = Group::query()->where('owner_id', Auth::user()->id)->find($id);
        if (!$model) {
            abort(404);
        }
        if($model->group_image){
            $this->anyDeleteImage($model);
        }
        if ($model->destroy($id)) {
            Notification::success('Group has been successfully deleted');
        }
        return ($url = Session::get('backUrl')) ? Redirect::to($url) : Redirect::to('/groups?type=my');
    }

    public function postRequestUsers()
    {
        $groupId = Input::get('group_id');
        $users = Input::get('users',false);
        if($users){
            $group = Group::find($groupId);
            $group->joinRequests()->attach($users);
            Notification::success('Request successfully sent');
        }
        else{
            Notification::error('Request has not been sent. Members have not been selected.');
        }

        return ($url = Session::get('backUrl'))
            ? Redirect::to($url)
            : Redirect::back();
    }

    public function anyAcceptRequest($groupId,$userId)
    {
        $group = Group::find($groupId);
        if($group){
            $group->joinRequests()->detach($userId);
            Auth::user()->joinGroup($group);
            return Request::ajax()?1:Redirect::back();
        }
        return Request::ajax()?0:Redirect::back();
    }

    public function anyCancelRequest($groupId,$userId)
    {
        $group = Group::find($groupId);
        if($group){
            $group->joinRequests()->detach($userId);
            return Request::ajax()?1:Redirect::back();
        }
        return Request::ajax()?0:Redirect::back();
    }

    public function getJoinGroup($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $group = Group::find($id);
        if(Auth::user()->isPremium()){
            $alreadyJoined = $group->members()->where('user_id',Auth::user()->id);
            if(!$alreadyJoined){
                Auth::user()->joinGroup($group);
                if(Request::ajax()){
                    return 1;
                }
            }
            else{
                Notification::error('You already joined this group');
            }
        }

        return ($url = Session::get('backUrl'))
            ? Redirect::to($url)
            : Redirect::back();
    }

    public function getLeaveGroup($id)
    {
        $group = Group::find($id);
        Auth::user()->leaveGroup($group);

        if(Request::ajax()){
            return 1;
        }

        return Redirect::back();
    }

    public function anyBanMember($groupId,$userId)
    {
        $group = Group::find($groupId);
        $group->members()->updateExistingPivot($userId, ['banned' => true]);

        if(Request::ajax()){
            return 1;
        }

        return Redirect::back();
    }

    public function anyUnbanMember($groupId,$userId)
    {
        $group = Group::find($groupId);
        $group->members()->updateExistingPivot($userId, ['banned' => false]);

        if(Request::ajax()){
            return 1;
        }

        return Redirect::back();
    }

    public function anyUploadImage($id = false)
    {
        $groupId = Request::get('group_id',$id);
        if (Input::hasFile('file')) {
            $file = Input::file('file');
//            var_dump($file);exit;
            $filePath = Config::get('app.groupImages').$groupId.'/';
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
                $group = Group::find($groupId);
                $this->anyDeleteImage($group);
                $group->group_image = $fileName;
                $group->save();
            }
            // Resizing 340x340
            Image::make($file->getRealPath())->fit(200, 200)->save($thumbPath)->destroy();
            return response()->json(array('filename'=> $fileName), 200);
        }
        return false;
    }

    public function anyDeleteImage(Group $model)
    {
        $groupId = Request::get('group_id',$model->id);
        if(!$filename = $model->group_image){
            $filename = Input::get('filename');
        }

        $group = Group::query();
        if($model->id){
            $group->where('id', $groupId);
        }
        else{
            $group->where('group_image', $filename);
        }
        $group = $group->first();
        
        if($group){
            $group->group_image = null;
            if($group->save()){
                $filePath = public_path(Config::get('app.groupImages').$group->id.'/'.$filename);
                $thumbPath = public_path(Config::get('app.groupImages').$group->id.'/thumbs/'.$filename);
                File::delete($filePath);
                File::delete($thumbPath);
            }
        }
        return response()->json($group, 200);
    }
}
