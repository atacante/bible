<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use App\Group;
use App\Http\Requests;

use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
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
        if(Auth::user()){
            $content['myGroups'] = $this->getMyGroups();
            $content['joinedGroups'] = $this->getJoinedGroups();
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

    public function getJoinGroup($id)
    {
        $group = Group::find($id);
        if(Auth::user()->isPremium()){
            Auth::user()->joinGroup($group);
            if(Request::ajax()){
                return 1;
            }
        }

        return ($url = Session::pull('back'))
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
