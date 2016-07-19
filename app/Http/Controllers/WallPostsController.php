<?php

namespace App\Http\Controllers;

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
//                Notification::success('Group has been successfully created');
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
        $offset = $limit*($page-1);

        $model = WallPost::/*with('comments')->*/find($id);
        if (!$model) {
            abort(404);
        }
        $model->type = 'status';

        $comments = $model->comments();
        $totalCount = $model->comments->count();
        $noteComments = $comments->limit($limit)->offset($offset)->get();

        $content['comments'] = $noteComments;
        $content['nextPage'] = ($limit*$page < $totalCount)?$page+1:false;

        $view = 'community.wall-comments';
        if($page > 1){
            $view = 'community.wall-comment-items';
        }

        return view($view, ['item' => $model,'content' => $content]);
    }

    public function anySaveComment(\Illuminate\Http\Request $request)
    {
        $note = WallPost::find(Input::get('id'));
        if (!$note) {
            abort(404);
        }

        $model = new WallComment();
        $text = Input::get('text');
        $data = ['user_id' => Auth::user()->id,'text' => $text];
        $this->validate($request, $model->rules());

        $commentCreated = $note->comments()->create($data);
        if ($commentCreated) {
            return view('community.wall-comment-item', ['comment' => $commentCreated]);
        }
        return 0;
    }
}
