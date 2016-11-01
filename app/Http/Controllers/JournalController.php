<?php

namespace App\Http\Controllers;

//use Illuminate\Http\Request;

use App\BaseModel;
use App\Http\Requests;
use App\Journal;
use App\Note;
use App\Prayer;
use App\WallComment;
use App\WallImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;
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
        return $this->redirectToBackUrl();
        /*
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
        */
    }

    public function anyCreate(\Illuminate\Http\Request $request)
    {
        $this->keepPreviousUrl();

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
            $this->validate($request, $model->rules(),$model->messages());
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
                $this->anyUploadImage($model->id);
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
                return $this->redirectToBackUrl();            }
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
        $this->keepPreviousUrl();

        $model = Journal::query()->where('user_id',Auth::user()->id)->find($id);
        $model->note_text = Input::get('note_text',false);
        $model->prayer_text = Input::get('prayer_text',false);
        if(!$model){
            abort(404);
        }
        if (Request::isMethod('put')) {
            $this->validate($request, $model->rules(),$model->messages());
            if ($model->update(Input::all())) {
                $this->anyUploadImage($model->id);
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
                return $this->redirectToBackUrl();
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
        $this->keepPreviousUrl();

        $model = Journal::query()->where('user_id',Auth::user()->id)->find($id);
        if(!$model){
            abort(404);
        }
        if ($model->destroy($id)) {
            Notification::success('Journal record has been successfully deleted');
        }

        return $this->redirectToBackUrl();
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

    public function getComments($id)
    {
        $limit = 5;
        $page = Input::get('page',1);
        $offset = $limit*($page-1);

        $model = Journal::/*with('comments')->*/find($id);
        if (!$model) {
            abort(404);
        }
        $model->type = 'journal';

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
        $note = Journal::find(Input::get('id'));
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

    public function getLikes($id,$type = 'simple')
    {
        $limit = 5;
        if($type == 'full'){
            $limit = 6;
        }
        $page = Input::get('page',1);
        $offset = $limit*($page-1);

        $model = Journal::find($id);
        if (!$model) {
            abort(404);
        }
        $model->type = 'journal';

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
        $model = Journal::find($id);
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
        $model = Journal::find($id);
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

    public function anyUploadImage($id = false)
    {
        $itemId = Request::get('item_id',$id);
        if (Input::hasFile('file')) {
            $files = Input::file('file');
            foreach ($files as $file) {
                $filePath = Config::get('app.journalImages') . $itemId . '/';
                if (!File::isDirectory(public_path() . $filePath)) {
                    File::makeDirectory(public_path() . $filePath, 0777, true);
                }
                $thumbPath = $filePath . 'thumbs/';
                $fileName = $itemId.'-'.time().'-'.$file->getClientOriginalName();

                if (!File::isDirectory(public_path() . $thumbPath)) {
                    File::makeDirectory(public_path() . $thumbPath, 0777, true);
                }

                $thumbPath = public_path($thumbPath . $fileName);
                if ($file) {
                    $item = Journal::find($itemId);
                    $item->images()->create(['user_id' => Auth::user()->id, 'image' => $fileName]);
                }
                // Resize to 800px width
                Image::make($file->getRealPath())->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save(public_path($filePath . $fileName))->destroy();
                // Make thumb
                Image::make($file->getRealPath())->fit(200, 200)->save($thumbPath)->destroy();
            }
            return true;
        }
        return false;
    }

    public function anyDeleteImage($filename = false)
    {
        if(!$filename){
            $filename = Input::get('filename');
        }

        $image = WallImage::query()->where('image', $filename)->first();

        if ($image) {
            $filePath = public_path(Config::get('app.journalImages').$image->item_id.'/'.$filename);
            $thumbPath = public_path(Config::get('app.journalImages').$image->item_id.'/thumbs/'.$filename);
            File::delete($filePath);
            File::delete($thumbPath);
            $image->delete();
        }

        return response()->json(true, 200);
    }
}
