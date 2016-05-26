<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\BaseModel;
use App\Helpers\ViewHelper;
use App\Journal;
use App\Note;
//use Illuminate\Http\Request;
use App\Prayer;
use App\Tag;
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
    protected $sortby;
    protected $order;

    private $searchFilter;
    private $dateFrom;
    private $dateTo;
    private $version;
    private $bookFilter;
    private $chapterFilter;
    private $verseFilter;
    private $tags;

    private function prepareFilters($noteModel)
    {
        $this->searchFilter = Request::input('search', false);
        $this->dateFrom = Request::input('date_from', false);
        $this->dateTo = Request::input('date_to', false);
        $this->version = Request::input('version', false);
        $this->bookFilter = Request::input('book', false);
        $this->chapterFilter = Request::input('chapter', false);
        $this->verseFilter = Request::input('verse', false);
        $this->tags = Request::input('tags', []);

        if (!empty($this->searchFilter)) {
            $noteModel->where('note_text', 'ilike', '%' . $this->searchFilter . '%');
        }

        if (!empty($this->dateFrom)) {
            $noteModel->whereRaw('created_at >= to_timestamp(' . strtotime($this->dateFrom . " 00:00:00") . ")");
        }

        if (!empty($this->dateTo)) {
            $noteModel->whereRaw('created_at <= to_timestamp(' . strtotime($this->dateTo . " 23:59:59") . ")");
        }

        if (!empty($this->version) && $this->version != 'all') {
            $noteModel->where('bible_version', $this->version);
        }

        if (!empty($this->bookFilter)) {
            $noteModel->whereHas('verse', function ($q) {
                $q->where('book_id', $this->bookFilter);
            });
        }

        if (!empty($this->chapterFilter)) {
            $noteModel->whereHas('verse', function ($q) {
                $q->where('chapter_num', $this->chapterFilter);
            });
        }

        if (!empty($this->verseFilter)) {
            $noteModel->whereHas('verse', function ($q) {
                $q->where('verse_num', $this->verseFilter);
            });
        }

        if (!empty($this->tags)) {
            $noteModel->whereHas('tags', function ($q) {
                $q->where(function($ow) {
                    foreach ($this->tags as $tag) {
                        $ow->orWhere('tag_id', $tag);
                    }
                });
            });
        }
        return $noteModel;
    }

    public function getList()
    {
        Session::flash('backUrl', Request::fullUrl());

        $this->sortby = Input::get('sortby', 'created_at');
        $this->order = Input::get('order', 'desc');

        $notesModel = Note::query();
        $notesModel = $this->prepareFilters($notesModel);

        $content['notes'] = $notesModel->with(['verse.booksListEn','journal','prayer','tags'])->where('user_id', Auth::user()->id)->orderBy($this->sortby, $this->order)->paginate(10);

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
        $model->bible_version = Input::get('version', false);
        $model->verse_id = Input::get('verse_id', false);
        if ($noteText = Input::get('text', false)) {
            $model->highlighted_text = $noteText;
        }
        $model->verse = false;
        if ($model->verse_id) {
            if ($model->bible_version) {
                $versesModel = BaseModel::getVersesModelByVersionCode($model->bible_version);
            } else {
                $versesModel = BaseModel::getVersesModelByVersionCode(Config::get('app.defaultBibleVersion'));
            }
            $model->verse = $versesModel::find($model->verse_id);
        }

        if (Request::isMethod('post') && Input::get('full_screen') == 0) {
            $this->validate($request, $model->rules());
            $data = Input::all();
            $data['user_id'] = Auth::user()->id;
            if ($model = $model->create($data)) {
                $model->syncTags(Input::get('tags'));
                if ($model->journal_text = Input::get('journal_text', false)) {
                    $model->journal_id = $this->saveJournal($model);
                    $model->save();
                }
                if ($model->prayer_text = Input::get('prayer_text', false)) {
                    $model->prayer_id = $this->savePrayer($model);
                    $model->save();
                }
                Notification::success('Note has been successfully created');
            }
            return ($url = Session::get('backUrl')) ? Redirect::to($url) : Redirect::to('/notes/list/');
        }
        $view = 'notes.create';
        if (Request::ajax()) {
            $view = 'notes.form';
        }
        return view($view, ['model' => $model,]);
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = Note::query()->where('user_id', Auth::user()->id)->find($id);
        $model->journal_text = Input::get('journal_text', false);
        $model->prayer_text = Input::get('prayer_text', false);
        if (!$model) {
            abort(404);
        }
        if (Request::isMethod('put')) {
            $this->validate($request, $model->rules());
            if ($model->update(Input::all())) {
                $model->syncTags(Input::get('tags'));

                if ($model->journal_text) {
                    $model->journal_id = $this->saveJournal($model);
                    $model->save();
                }
                if ($model->prayer_text) {
                    $model->prayer_id = $this->savePrayer($model);
                    $model->save();
                }
                Notification::success('Note has been successfully updated');
            }
            return ($url = Session::get('backUrl')) ? Redirect::to($url) : Redirect::to('/notes/list/');
        }
        return view('notes.update', ['model' => $model]);
    }

    public function anyDelete($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = Note::query()->where('user_id', Auth::user()->id)->find($id);
        if (!$model) {
            abort(404);
        }
        if ($model->destroy($id)) {
            Notification::success('Note has been successfully deleted');
        }
        return ($url = Session::get('backUrl')) ? Redirect::to($url) : Redirect::to('/notes/list/');
    }

    private function saveJournal($model)
    {
        $journalModel = new Journal();
        if ($model->journal) {
            $journalModel = $model->journal;
        }
        $journalModel->user_id = $model->user_id;
        $journalModel->verse_id = $model->verse_id;
        $journalModel->lexicon_id = $model->lexicon_id;
        $journalModel->note_id = $model->id;
        $journalModel->bible_version = $model->bible_version;
        $journalModel->highlighted_text = $model->highlighted_text;
        $journalModel->journal_text = $model->journal_text;
        if ($journalModel->save()) {
            return $journalModel->id;
        }
    }

    private function savePrayer($model)
    {
        $prayerModel = new Prayer();
        if ($model->prayer) {
            $prayerModel = $model->prayer;
        }
        $prayerModel->user_id = $model->user_id;
        $prayerModel->verse_id = $model->verse_id;
        $prayerModel->lexicon_id = $model->lexicon_id;
        $prayerModel->note_id = $model->id;
        $prayerModel->bible_version = $model->bible_version;
        $prayerModel->highlighted_text = $model->highlighted_text;
        $prayerModel->prayer_text = $model->prayer_text;
        if ($prayerModel->save()) {
            return $prayerModel->id;
        }
    }

    public function anyTags()
    {
        return Tag::where('user_id', Auth::user()->id)->get();
    }

    public function postSaveTag(\Illuminate\Http\Request $request)
    {
        $tagModel = new Tag();
        $this->validate($request, $tagModel->rules());
        if ($tagModel->create(Input::all())) {//$tagModel->notes()->sync(Input::get('tags',[]))
            $tagModel->notes()->attach($tagModel->id);
            return true;
        }
        return false;
    }

    public function anyDeleteTag($id)
    {
        $tagModel = Tag::query()->where('user_id', Auth::user()->id)->find($id);
        if (!$tagModel) {
            abort(404);
        }
        if ($tagModel->destroy($id)) {
            return true;
        }
        return false;
    }
}
