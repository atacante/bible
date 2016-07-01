<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

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

class JournalController extends Controller
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
    private $tags;

    private function prepareFilters($journalModel){
        $this->searchFilter = Request::input('search', false);
        $this->dateFrom = Request::input('date_from', false);
        $this->dateTo = Request::input('date_to', false);
        $this->version = Request::input('version', false);
        $this->bookFilter = Request::input('book', false);
        $this->chapterFilter = Request::input('chapter', false);
        $this->verseFilter = Request::input('verse', false);
        $this->tags = Request::input('tags', []);

        if(!empty($this->searchFilter)){
            $journalModel->where('journal_text', 'ilike', '%'.$this->searchFilter.'%');
        }

        if(!empty($this->dateFrom)){
            $journalModel->whereRaw('created_at >= to_timestamp('.strtotime($this->dateFrom." 00:00:00").")");
        }

        if(!empty($this->dateTo)){
            $journalModel->whereRaw('created_at <= to_timestamp('.strtotime($this->dateTo." 23:59:59").")");
        }

        if(!empty($this->version) && $this->version != 'all'){
            $journalModel->where('bible_version', $this->version);
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
        if (!empty($this->tags)) {
            $journalModel->whereHas('tags', function ($q) {
                $q->where(function($ow) {
                    foreach ($this->tags as $tag) {
                        $ow->orWhere('tag_id', $tag);
                    }
                });
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

        $content['journal'] = $notesModel->with(['verse.booksListEn','note','prayer','tags'])->where('user_id',Auth::user()->id)->orderBy($this->sortby,$this->order)->paginate(10);

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

        if($journalText = Input::get('text',false)){
            $model->highlighted_text = $journalText;
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

        if (Request::isMethod('post') && Input::get('full_screen') == 0) {
            $this->validate($request, $model->rules());
            $data = Input::all();
            $data['user_id'] = Auth::user()->id;
            if(!$model->verse){
                if($rel = Input::get('rel', false)){
                    $data['rel_code'] = $rel;
                }
                else{
                    $data['rel_code'] = BaseModel::generateRelationCode();
                }
            }
            if ($model = $model->create($data)) {
                $model->syncTags(Input::get('tags'));
                $model->syncGroups();
                if($model->note_text = Input::get('note_text',false)){
                    $model->note_id = $this->saveNote($model);
                    $model->save();
                }
                if($model->prayer_text = Input::get('prayer_text',false)){
                    $model->prayer_id = $this->savePrayer($model);
                    $model->save();
                }
                Notification::success('Journal record has been successfully created');
            }
            if(!Request::ajax()) {
                return ($url = Session::get('backUrl')) ? Redirect::to($url) : Redirect::to('/journal/list/');
            }
            else{
                return 1;
            }
        }

        $myGroups = Auth::user()->myGroups()->pluck('group_name','groups.id')->toArray();
        $joinedGroups = Auth::user()->joinedGroups()->pluck('group_name','groups.id')->toArray();
        $content['groups'] = $myGroups+$joinedGroups;

        $view = 'journal.create';
        if(Request::ajax()){
            $view = 'journal.form';
        }

        return view($view,
            [
                'model' => $model,
                'content' => $content
            ]);
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = Journal::query()->where('user_id',Auth::user()->id)->find($id);
        $model->note_text = Input::get('note_text',false);
        $model->prayer_text = Input::get('prayer_text',false);
        if(!$model){
            abort(404);
        }
        if (Request::isMethod('put')) {
            $this->validate($request, $model->rules());
            if ($model->update(Input::all())) {
                $model->syncTags(Input::get('tags'));
                $model->syncGroups();
                if($model->note_text){
                    $model->note_id = $this->saveNote($model);
                    $model->save();
                }
                if($model->prayer_text){
                    $model->prayer_id = $this->savePrayer($model);
                    $model->save();
                }
                Notification::success('Journal record has been successfully updated');
            }
            if(!Request::ajax()) {
                return ($url = Session::get('backUrl')) ? Redirect::to($url) : Redirect::to('/journal/list/');
            }
            else{
                return 1;
            }
        }

        $view = 'journal.update';
        if(Request::ajax()){
            $view = 'journal.form';
        }

        $myGroups = Auth::user()->myGroups()->pluck('group_name','groups.id')->toArray();
        $joinedGroups = Auth::user()->joinedGroups()->pluck('group_name','groups.id')->toArray();
        $content['groups'] = $myGroups+$joinedGroups;

        return view($view,
            [
                'model' => $model,
                'content' => $content
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

    private function saveNote($model)
    {
        $noteModel = new Note();
        if($model->note){
            $noteModel = $model->note;
        }
        $noteModel->user_id = $model->user_id;
        $noteModel->verse_id = $model->verse_id;
        $noteModel->lexicon_id = $model->lexicon_id;
        $noteModel->journal_id = $model->id;
        $noteModel->bible_version = $model->bible_version;
        $noteModel->highlighted_text = $model->highlighted_text;
        $noteModel->note_text = $model->note_text;
        $noteModel->rel_code = $model->rel_code;
        if($noteModel->save()){
            return $noteModel->id;
        }
    }

    private function savePrayer($model)
    {
        $prayerModel = new Prayer();
        if($model->prayer){
            $prayerModel = $model->prayer;
        }
        $prayerModel->user_id = $model->user_id;
        $prayerModel->verse_id = $model->verse_id;
        $prayerModel->lexicon_id = $model->lexicon_id;
        $prayerModel->journal_id = $model->id;
        $prayerModel->bible_version = $model->bible_version;
        $prayerModel->highlighted_text = $model->highlighted_text;
        $prayerModel->prayer_text = $model->prayer_text;
        $prayerModel->rel_code = $model->rel_code;
        if($prayerModel->save()){
            return $prayerModel->id;
        }
    }
}
