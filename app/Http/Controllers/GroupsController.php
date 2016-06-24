<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use App\Group;
use App\Http\Requests;

use App\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
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
}
