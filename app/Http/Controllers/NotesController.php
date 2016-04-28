<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\BaseModel;
use App\Helpers\ViewHelper;
use App\Note;
//use Illuminate\Http\Request;
use App\VersionsListEn;
use FineDiffTests\Usage\Base;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Krucas\Notification\Facades\Notification;

class NotesController extends Controller
{
    protected  $sortby;
    protected  $order;

    private $searchFilter;
    private $bookFilter;
    private $chapterFilter;
    private $verseFilter;

    private function prepareFilters($noteModel){
        $this->searchFilter = Request::input('search', false);
        $this->bookFilter = Request::input('book', false);
        $this->chapterFilter = Request::input('chapter', false);
        $this->verseFilter = Request::input('verse', false);

        if(!empty($this->searchFilter)){
            $noteModel->where('note_text', 'ilike', '%'.$this->searchFilter.'%');
        }

        if(!empty($this->bookFilter)){
            $noteModel->whereHas('verse', function($q){
                $q->where('book_id',$this->bookFilter);
            });
        }

        if(!empty($this->chapterFilter)){
            $noteModel->whereHas('verse', function($q){
                $q->where('chapter_num',$this->chapterFilter);
            });
        }

        if(!empty($this->verseFilter)){
            $noteModel->whereHas('verse', function($q){
                $q->where('verse_num',$this->verseFilter);
            });
        }
        return $noteModel;
    }

    public function getList(){
        Session::flash('backUrl', Request::fullUrl());

        $this->sortby = Input::get('sortby','created_at');
        $this->order = Input::get('order','desc');

        $notesModel = Note::query();
        $notesModel = $this->prepareFilters($notesModel);

        $content['notes'] = $notesModel->with('verse.booksListEn')->where('user_id',Auth::user()->id)->orderBy($this->sortby,$this->order)->paginate(10);

        $content['action'] = 'notes/list';
        $content['columns'] = Note::$columns;

        $content['sortby'] = $this->sortby;
        $content['order'] = $this->order;

        return view('notes.list', ['content' => $content]);
    }

    public function anyCreate(\Illuminate\Http\Request $request)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }

        $model = new Note();
        $model->bible_version = Input::get('version',false);
        $model->verse_id = Input::get('verse_id',false);
        $model->note_text = Input::get('text',false);
        if($model->note_text){
            $model->note_text = "<i>".$model->note_text."</i><p></p>";
        }
        $model->verse = false;
        if($model->verse_id){
            if($model->bible_version){
                $versesModel = BaseModel::getVersesModelByVersionCode($model->bible_version);
            }
            else{
                $versesModel = BaseModel::getVersesModelByVersionCode(Config::get('app.defaultBibleVersion'));
            }
            $model->verse = $versesModel::find($model->verse_id);
        }

        if (Request::isMethod('post')) {
            $this->validate($request, $model->rules());
            $data = Input::all();
            $data['user_id'] = Auth::user()->id;
            if ($model = $model->create($data)) {
                Notification::success('Note has been successfully created');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to('/notes/list/');
        }
        return view('notes.create',
            [
                'model' => $model,
            ]);
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = Note::query()->where('user_id',Auth::user()->id)->find($id);
        if(!$model){
            abort(404);
        }
        if (Request::isMethod('put')) {
            $this->validate($request, $model->rules());
            if ($model->update(Input::all())) {
                Notification::success('Note has been successfully updated');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to('/notes/list/');
        }
        return view('notes.update',
            [
                'model' => $model
            ]);
    }

    public function anyDelete($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = Note::query()->where('user_id',Auth::user()->id)->find($id);
        if(!$model){
            abort(404);
        }
        if ($model->destroy($id)) {
            Notification::success('Note has been successfully deleted');
        }
        return ($url = Session::get('backUrl'))
            ? Redirect::to($url)
            : Redirect::to('/notes/list/');
    }
}
