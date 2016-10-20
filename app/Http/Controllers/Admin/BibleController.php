<?php

namespace App\Http\Controllers\Admin;

use App\BaseModel;
use App\Helpers\ViewHelper;
use App\Location;
use App\LocationVerse;
use App\People;
use App\VerseOfDay;
use App\VersionsListEn;
use Illuminate\Http\Request as HttpRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Krucas\Notification\Facades\Notification;

class BibleController extends Controller
{
    public function getVersions()
    {
        $content['versions'] = VersionsListEn::allVersionsList();
        return view('admin.bible.versions', ['page_title' => "Bible Versions",'content' => $content]);
    }

    public function getVerses($code)
    {
        Session::flash('backUrl', Request::fullUrl());

        $book = Request::input('book', false);
        $chapter = Request::input('chapter', false);
        $verse = Request::input('verse', false);

        $version = VersionsListEn::getVersionByCode($code);
        $versesModel = BaseModel::getVersesModelByVersionCode($code);

        $verses = $versesModel::query()->with('booksListEn');
        if(!empty($book)){
            $verses->where('book_id',$book);
        }

        if(!empty($chapter)){
            $verses->where('chapter_num',$chapter);
        }

        if(!empty($verse)){
            $verses->where('verse_num',$verse);
        }
        $content['verses'] = $verses->orderBy('book_id')->orderBy('chapter_num')->orderBy('verse_num')->paginate(20);
        return view('admin.bible.verses',
            [
                'page_title' => $version.' Verses',
                'content' => $content,
                'filterAction' => 'bible/verses/'.$code,
                'versionCode' => $code,
                'versionName' => $version
            ]);
    }

    public function anyUpdate(HttpRequest $request,$code,$id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $versesModel = BaseModel::getVersesModelByVersionCode($code);
        $version = VersionsListEn::getVersionByCode($code);
        $verse = $versesModel::find($id);
        $locations = ViewHelper::prepareForSelectBox(Location::query()->get()->toArray(),'id','location_name');
        $peoples = ViewHelper::prepareForSelectBox(People::query()->get()->toArray(),'id','people_name');
        if (Request::isMethod('put')) {
            $this->validate($request, [
                'verse_text' => 'required',
            ]);
            if($verse->update(Input::all())){
//                $this->updateLocations($verse);
                $verse->locations()->sync(Input::get('locations',[]));
                $verse->peoples()->sync(Input::get('peoples',[]));
                Notification::success('Verse has been successfully updated');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to(ViewHelper::adminUrlSegment().'/bible/verses/'.$code);
        }
        return view('admin.bible.update',
            [
                'page_title' => 'Update Bible Verse',
                'model' => $verse,
                'version' => $version,
                'versionCode' => $code,
                'versionName' => $version,
                'locations' => $locations,
                'peoples' => $peoples,
            ]);
    }

    private function updateLocations($verse){
        LocationVerse::where('verse_id', $verse->id)->delete();
        $locations = Input::get('locations');
        if(count($locations)){
            foreach ($locations as $location) {
                LocationVerse::create([
                    'verse_id' => $verse->id,
                    'location_id' => $location,
                    'book_id' => $verse->book_id,
                    'chapter_num' => $verse->chapter_num,
                    'verse_num' => $verse->verse_num
                ]);
            }
        }
    }

    public function anyVerseday(){
        if (Request::isMethod('post')) {
            $data = Input::all();

            if(!isset($data['book']) || !isset($data['chapter']) || !isset($data['verse'])){
                Notification::error('You should select book, chapter and verse');

                return ($url = Session::get('backUrl'))
                    ? Redirect::to($url)
                    : Redirect::to(ViewHelper::adminUrlSegment().'/bible/verseday');
            }

            $book_id = $data['book'];
            $chapter_id = $data['chapter'];
            $verse_num = $data['verse'];

            $verse = BaseModel::getVerse($book_id, $chapter_id, $verse_num);
            $previousVerse = VerseOfDay::getTodayVerse();

            $tomorrow = (bool) $data['tomorrow'];

            if($verseOfDay = VerseOfDay::createById($verse->id, $tomorrow)){
                if(!$this->anyUploadImage($verseOfDay)){
                      $verseOfDay->image = $previousVerse->image;
                      $verseOfDay->save();
                }
                Notification::success('Verse Of The Day has been successfully updated');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to(ViewHelper::adminUrlSegment().'/bible/verseday');
        }

        $verseOfDay = VerseOfDay::getTodayVerse();

        return view('admin.bible.verse_of_day', [
            'page_title' => 'Verse Of The Day',
            'verseOfDay' => $verseOfDay
        ]);
    }

    public function anyUploadImage($model)
    {
        if (Input::hasFile('file')) {
            $file = Input::file('file');

            $tmpFilePath = Config::get('app.verseOfDayImages');
            $tmpThumbPath = $tmpFilePath . 'thumbs/';
            $tmpFileName = time() . '-' . $file->getClientOriginalName();
            $file = $file->move(public_path() . $tmpFilePath, $tmpFileName);
            $path = $tmpFilePath . $tmpFileName;

            $this->makeDir(public_path() . $tmpThumbPath);
            $thumbPath = public_path($tmpThumbPath . $tmpFileName);
            if($file){
                $model->image = $tmpFileName;
                $model->save();
            }
            // Resizing 340x340
            Image::make($file->getRealPath())->fit(100, 100)->save($thumbPath)->destroy();

            return true;
        }
        return false;
    }

    private function makeDir($path)
    {
        if (!is_dir($path)) {
            return mkdir($path);
        }
        return true;
    }
}
