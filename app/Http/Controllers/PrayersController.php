<?php

namespace App\Http\Controllers;

use App\BaseModel;
use App\Http\Requests;
use App\Journal;
use App\Note;
use App\Prayer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Krucas\Notification\Facades\Notification;

class PrayersController extends Controller
{
    protected  $sortby;
    protected  $order;

    private $searchFilter;
    private $dateFrom;
    private $dateTo;
    private $version;
    private $bookFilter;
    private $chapterFilter;
    private $verseFilter;

    private function prepareFilters($prayerModel){
        $this->searchFilter = Request::input('search', false);
        $this->dateFrom = Request::input('date_from', false);
        $this->dateTo = Request::input('date_to', false);
        $this->version = Request::input('version', false);
        $this->bookFilter = Request::input('book', false);
        $this->chapterFilter = Request::input('chapter', false);
        $this->verseFilter = Request::input('verse', false);

        if(!empty($this->searchFilter)){
            $prayerModel->where('prayer_text', 'ilike', '%'.$this->searchFilter.'%');
        }

        if(!empty($this->dateFrom)){
            $prayerModel->whereRaw('created_at >= to_timestamp('.strtotime($this->dateFrom." 00:00:00").")");
        }

        if(!empty($this->dateTo)){
            $prayerModel->whereRaw('created_at <= to_timestamp('.strtotime($this->dateTo." 23:59:59").")");
        }

        if(!empty($this->version) && $this->version != 'all'){
            $prayerModel->where('bible_version', $this->version);
        }

        if(!empty($this->bookFilter)){
            $prayerModel->whereHas('verse', function($q){
                $q->where('book_id',$this->bookFilter);
            });
        }

        if(!empty($this->chapterFilter)){
            $prayerModel->whereHas('verse', function($q){
                $q->where('chapter_num',$this->chapterFilter);
            });
        }

        if(!empty($this->verseFilter)){
            $prayerModel->whereHas('verse', function($q){
                $q->where('verse_num',$this->verseFilter);
            });
        }
        return $prayerModel;
    }

    public function getList(){
        Session::flash('backUrl', Request::fullUrl());

        $this->sortby = Input::get('sortby','created_at');
        $this->order = Input::get('order','desc');

        $prayerModel = Prayer::query();
        $prayerModel = $this->prepareFilters($prayerModel);

        $content['prayers'] = $prayerModel->with('verse.booksListEn')->where('user_id',Auth::user()->id)->orderBy($this->sortby,$this->order)->paginate(10);

        $content['action'] = 'prayers/list';
        $content['columns'] = Prayer::$columns;

        $content['sortby'] = $this->sortby;
        $content['order'] = $this->order;

        return view('prayers.list', ['content' => $content]);
    }

    public function anyCreate(\Illuminate\Http\Request $request)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }

        $model = new Prayer();
        $model->bible_version = Input::get('version',false);
        $model->verse_id = Input::get('verse_id',false);
        $model->prayer_text = Input::get('text',false);

        if($model->prayer_text){
            $model->prayer_text = "<i>".$model->prayer_text."</i><p></p>";
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
                if($model->note_text = Input::get('note_text',false)){
                    $model->note_id = $this->saveNote($model);
                    $model->save();
                }
                if($model->journal_text = Input::get('journal_text',false)){
                    $model->journal_id = $this->saveJournal($model);
                    $model->save();
                }
                Notification::success('Prayer record has been successfully created');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to('/prayers/list/');
        }

        return view('prayers.create',
            [
                'model' => $model,
            ]);
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = Prayer::query()->where('user_id',Auth::user()->id)->find($id);
        $model->journal_text = Input::get('journal_text',false);
        $model->note_text = Input::get('note_text',false);
        if(!$model){
            abort(404);
        }
        if (Request::isMethod('put')) {
            $this->validate($request, $model->rules());
            if ($model->update(Input::all())) {
                if($model->note_text){
                    $model->note_id = $this->saveNote($model);
                    $model->save();
                }
                if($model->journal_text = Input::get('journal_text',false)){
                    $model->journal_id = $this->saveJournal($model);
                    $model->save();
                }
                Notification::success('Prayer has been successfully updated');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to('/prayers/list/');
        }
        return view('prayers.update',
            [
                'model' => $model
            ]);
    }

    public function anyDelete($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = Prayer::query()->where('user_id',Auth::user()->id)->find($id);
        if(!$model){
            abort(404);
        }
        if ($model->destroy($id)) {
            Notification::success('Prayer record has been successfully deleted');
        }
        return ($url = Session::get('backUrl'))
            ? Redirect::to($url)
            : Redirect::to('/prayers/list/');
    }

    private function saveNote($model)
    {
        $noteModel = new Note();
        if($model->note){
            $noteModel = $model->note;
        }
        $noteModel->user_id = $model->user_id;
        $noteModel->verse_id = $model->verse_id;
        $noteModel->lexicon_id = $model->lexicon_id;
        $noteModel->prayer_id = $model->id;
        $noteModel->bible_version = $model->bible_version;
        $noteModel->note_text = $model->note_text;
        if($noteModel->save()){
            return $noteModel->id;
        }
    }

    private function saveJournal($model)
    {
        $journalModel = new Journal();
        if($model->journal){
            $journalModel = $model->journal;
        }
        $journalModel->user_id = $model->user_id;
        $journalModel->verse_id = $model->verse_id;
        $journalModel->lexicon_id = $model->lexicon_id;
        $journalModel->prayer_id = $model->id;
        $journalModel->bible_version = $model->bible_version;
        $journalModel->journal_text = $model->journal_text;
        if($journalModel->save()){
            return $journalModel->id;
        }
    }
}
