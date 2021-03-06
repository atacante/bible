<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ViewHelper;
use App\Location;
//use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\LocationImages;
use App\VersesKingJamesEn;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Kodeine\Acl\Models\Eloquent\Role;
use Krucas\Notification\Facades\Notification;
use Proengsoft\JsValidation\Facades\JsValidatorFacade;

class LocationController extends Controller
{
    private $searchFilter;
    private $bookFilter;
    private $chapterFilter;
    private $verseFilter;

    private function prepareFilters($locationsModel){
        $this->searchFilter = Request::input('search', false);
        $this->bookFilter = Request::input('book', false);
        $this->chapterFilter = Request::input('chapter', false);
        $this->verseFilter = Request::input('verse', false);

        if(!empty($this->searchFilter)){
            $locationsModel->where('location_name', 'ilike', '%'.$this->searchFilter.'%');
//            $locationsModel->orWhere('location_description', 'ilike', '%'.$this->searchFilter.'%');
        }

        if(!empty($this->bookFilter)){
            $locationsModel->whereHas('verses', function($q){
                $q->where('book_id',$this->bookFilter);
            });
        }

        if(!empty($this->chapterFilter)){
            $locationsModel->whereHas('verses', function($q){
                $q->where('chapter_num',$this->chapterFilter);
            });
        }

        if(!empty($this->verseFilter)){
            $locationsModel->whereHas('verses', function($q){
                $q->where('verse_num',$this->verseFilter);
            });
        }
        return $locationsModel;
    }

    public function getList()
    {
        Session::flash('backUrl', Request::fullUrl());

        $locationsModel = new Location();

        $locationsModel = $this->prepareFilters($locationsModel->query());

        $content['locations'] = $locationsModel->with('images')->orderBy('location_name')->/*orderBy('chapter_num')->orderBy('verse_num')->*/
        paginate(20);
        return view('admin.location.list',
            [
                'page_title' => 'Locations',
                'content' => $content,
                'filterAction' => 'location/list/',
            ]);
    }

    public function anyCreate(\Illuminate\Http\Request $request)
    {
        $model = new Location();
        $jsValidator = JsValidatorFacade::make($model->rules());
        if (Request::isMethod('post')) {
            $this->validate($request, $model->rules());
            if ($model = $model->create(Input::all())) {
                $this->anyUploadImage($model->id);
                Notification::success('Location has been successfully created');
            }
            return Redirect::to(ViewHelper::adminUrlSegment() . '/location/list/');
        }
        return view('admin.location.create',
            [
                'model' => $model,
                'jsValidator' => $jsValidator,
                'page_title' => 'Create New Location'
            ]);
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = Location::query()->with('images')->find($id);
        $validator = JsValidatorFacade::make($model->rules());
        if (Request::isMethod('put')){
            $this->validate($request, $model->rules());
            $data = Input::all();
            $data['associate_verses'] = (boolean)$data['associate_verses'];
            if ($model->update($data)) {
                $this->anyUploadImage($model->id);
                Notification::success('Location has been successfully updated');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to(ViewHelper::adminUrlSegment() . '/location/list/');
        }
        return view('admin.location.update',
            [
                'model' => $model,
                'jsValidator' => $validator,
                'page_title' => 'Edit Location',
            ]);
    }

    public function anyDelete($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $location = Location::query()->with('images')->find($id);
        if($location->images){
            foreach ($location->images as $image) {
                $this->anyDeleteImage($image->image);
            }
        }
        if (Location::destroy($id)) {
            Notification::success('Location has been successfully deleted');
        }
        return ($url = Session::get('backUrl'))
            ? Redirect::to($url)
            : Redirect::to(ViewHelper::adminUrlSegment() . '/location/list/');
    }

    public function anyUploadImage($locationId)
    {
        if (Input::hasFile('file')) {
            $files = Input::file('file');
            foreach ($files as $file) {
                $tmpFilePath = Config::get('app.locationImages');
                $tmpThumbPath = $tmpFilePath . 'thumbs/';
                $tmpFileName = time() . '-' . $file->getClientOriginalName();
                $file = $file->move(public_path() . $tmpFilePath, $tmpFileName);
                $path = $tmpFilePath . $tmpFileName;

                $this->makeDir(public_path() . $tmpThumbPath);
                $thumbPath = public_path($tmpThumbPath . $tmpFileName);
                if($file){
                    $image = new LocationImages();
                    $image->location_id = $locationId;
                    $image->image = $tmpFileName;
                    $image->save();
                }
                // Resizing 340x340
                Image::make($file->getRealPath())->fit(200, 200)->save($thumbPath)->destroy();

            }
//            return response()->json(array('filename'=> $tmpFileName), 200);
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

    public function anyDeleteImage($filename = false)
    {
        if(!$filename){
            $filename = Input::get('filename');
        }

        $image = LocationImages::query()->where('image', $filename)->first();

        if ($image) {
            $image->delete();
        }

        $this->unlinkLocationImage($filename);
        return response()->json(true, 200);
    }

    private function unlinkLocationImage($filename)
    {
        $tmpFilePath = public_path(Config::get('app.locationImages').$filename);
        $tmpThumbPath = public_path(Config::get('app.locationImages').'thumbs/'.$filename);

        if (is_file($tmpFilePath)) {
            unlink($tmpFilePath);
        }
        if (is_file($tmpThumbPath)) {
            unlink($tmpThumbPath);
        }
    }

    public function anyVerses($id)
    {
        $location = Location::query()->with('verses')->find($id);
    }

    public function anyLocations($id)
    {
        $verse = VersesKingJamesEn::query()->with('locations')->find($id);
    }
}
