<?php

namespace App\Http\Controllers;

use App\Group;
use App\Helpers\NotificationsHelper;
use App\Http\Requests;
use App\WallComment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use App\WallPost;
use Krucas\Notification\Facades\Notification;

class WallPostsController extends Controller
{
    public function anyPostStatus(\Illuminate\Http\Request $request)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }

        $model = new WallPost();

        if (Request::isMethod('post')) {
            $this->validate($request, $model->rules());
            $data = Input::all();
            $data['user_id'] = Auth::user()->id;
            if ($model = $model->create($data)) {
                if($model->rel_id){
                    $group = Group::find($model->rel_id);
                    if($model->access_level != 'private'){
                        NotificationsHelper::groupWallItem($group);
                    }
                }
            }
            if (!Request::ajax()) {
                return ($url = Session::get('backUrl')) ? Redirect::to($url) : Redirect::back();
            }
            else{
                return 1;
            }
        }
    }

    public function getComments($id)
    {
        $limit = 5;
        $page = Input::get('page',1);
        if($page == 'all'){
            $limit = null;
        }

        $model = WallPost::/*with('comments')->*/find($id);
        if (!$model) {
            abort(404);
        }
        $model->type = 'status';

        $comments = $model->comments();
        $totalCount = $model->comments->count();
        $noteComments = $comments->limit($limit)->get();

        $content['comments'] = $noteComments->reverse();
        $content['otherCommentsCount'] = $limit?$totalCount-$limit:0;

        $view = 'community.wall-comments';
        if($page > 1){
            $view = 'community.wall-comment-items';
        }

        return view($view, ['item' => $model,'content' => $content]);
    }

    public function anySaveComment(\Illuminate\Http\Request $request)
    {
        $note = WallPost::find(Input::get('id'));
        $note->type = 'status';
        if (!$note) {
            abort(404);
        }

        $model = new WallComment();
        $text = Input::get('text');
        $data = ['user_id' => Auth::user()->id,'text' => $text];
        $this->validate($request, $model->rules());

        $commentCreated = $note->comments()->create($data);
        if ($commentCreated) {
            NotificationsHelper::publicWallItemComment($note);
            return view('community.wall-comment-item', ['comment' => $commentCreated,'item' => $note]);
        }
        return 0;
    }

    public function anyDeleteComment($id)
    {
        if (!Auth::check()) {
            abort(403);
        }
        $model = WallPost::whereHas('comments', function ($q) use($id) {
            $q->where('id',$id);
            $q->where('user_id',Auth::user()->id);
        })->first();
        if (!$model) {
            abort(404);
        }
        if($model->comments()->where('id',$id)->delete()){
            return 1;
        }
        return 0;
    }

    public function getLikes($id,$type = 'simple')
    {
        $limit = 5;
        if($type == 'full'){
            $limit = 6;
        }
        $page = Input::get('page',1);
        $offset = $limit*($page-1);

        $model = WallPost::find($id);
        if (!$model) {
            abort(404);
        }
        $model->type = 'status';

        $likes = $model->likes();
        $totalCount = $model->likes->count();
        $likes = $likes->limit($limit)->offset($offset)->get();

        $content['likes'] = $likes;

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

        $content['nextPage'] = ($limit*$page < $totalCount)?$page+1:false;

        return view('community.wall-likes-'.$type, [
            'item' => $model,
            'content' => $content,
            'myFriends' => $myFriends,
            'requests' => $requests,
            'ignoredRequests' => $ignoredRequests,
            'myRequests' => $myRequests
        ]);
    }

    public function anySaveLike($id)
    {
        $model = WallPost::find($id);
        if (!$model) {
            abort(404);
        }
        $liked = 0;
        if(!$model->likes()->where('user_id',Auth::user()->id)->get()->count()){
            $model->likes()->attach(Auth::user()->id);
            $liked = 1;
        }
        return $liked;
    }

    public function anyRemoveLike($id)
    {
        $model = WallPost::find($id);
        if (!$model) {
            abort(404);
        }

        $unliked = 0;
        if($model->likes()->where('user_id',Auth::user()->id)->get()->count()){
            $model->likes()->detach(Auth::user()->id);
            $unliked = 1;
        }
        return $unliked;
    }
}
