<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use App\BaseModel;
use App\Http\Requests;
use App\Journal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Krucas\Notification\Facades\Notification;

class JournalController extends Controller
{
    protected  $sortby;
    protected  $order;

    private $searchFilter;
    private $bookFilter;
    private $chapterFilter;
    private $verseFilter;

    private function prepareFilters($journalModel){
        $this->searchFilter = Request::input('search', false);
        $this->bookFilter = Request::input('book', false);
        $this->chapterFilter = Request::input('chapter', false);
        $this->verseFilter = Request::input('verse', false);

        if(!empty($this->searchFilter)){
            $journalModel->where('journal_text', 'ilike', '%'.$this->searchFilter.'%');
        }

        if(!empty($this->bookFilter)){
            $journalModel->whereHas('verse', function($q){
                $q->where('book_id',$this->bookFilter);
            });
        }

        if(!empty($this->chapterFilter)){
            $journalModel->whereHas('verse', function($q){
                $q->where('chapter_num',$this->chapterFilter);
            });
        }

        if(!empty($this->verseFilter)){
            $journalModel->whereHas('verse', function($q){
                $q->where('verse_num',$this->verseFilter);
            });
        }
        return $journalModel;
    }

    public function getList(){
        Session::flash('backUrl', Request::fullUrl());

        $this->sortby = Input::get('sortby','created_at');
        $this->order = Input::get('order','desc');

        $notesModel = Journal::query();
        $notesModel = $this->prepareFilters($notesModel);

        $content['journal'] = $notesModel->with('verse.booksListEn')->where('user_id',Auth::user()->id)->orderBy($this->sortby,$this->order)->paginate(10);

        $content['action'] = 'journal/list';
        $content['columns'] = Journal::$columns;

        $content['sortby'] = $this->sortby;
        $content['order'] = $this->order;

        return view('journal.list', ['content' => $content]);
    }

    public function anyCreate(\Illuminate\Http\Request $request)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }

        $model = new Journal();
        $model->bible_version = Input::get('version',false);
        $model->verse_id = Input::get('verse_id',false);
        $model->journal_text = Input::get('text',false);
        if($model->journal_text){
            $model->journal_text = "<i>".$model->journal_text."</i><p></p>";
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
                Notification::success('Journal record has been successfully created');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to('/journal/list/');
        }
        return view('journal.create',
            [
                'model' => $model,
            ]);
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = Journal::query()->where('user_id',Auth::user()->id)->find($id);
        if(!$model){
            abort(404);
        }
        if (Request::isMethod('put')) {
            $this->validate($request, $model->rules());
            if ($model->update(Input::all())) {
                Notification::success('Journal record has been successfully updated');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to('/journal/list/');
        }
        return view('journal.update',
            [
                'model' => $model
            ]);
    }

    public function anyDelete($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = Journal::query()->where('user_id',Auth::user()->id)->find($id);
        if(!$model){
            abort(404);
        }
        if ($model->destroy($id)) {
            Notification::success('Journal record has been successfully deleted');
        }
        return ($url = Session::get('backUrl'))
            ? Redirect::to($url)
            : Redirect::to('/journal/list/');
    }
}
