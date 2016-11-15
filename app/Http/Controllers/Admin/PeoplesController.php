<?php

namespace App\Http\Controllers\Admin;

//use Illuminate\Http\Request;

use App\BaseModel;
use App\Helpers\ViewHelper;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\People;
use App\PeopleImages;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Krucas\Notification\Facades\Notification;

class PeoplesController extends Controller
{
    public function getList()
    {
        Session::flash('backUrl', Request::fullUrl());

        $peoplesModel = People::query();

        if (!empty($search = Request::input('search', false))) {
            $peoplesModel->where('people_name', 'ilike', '%'.$search.'%');
            $peoplesModel->orWhere('people_description', 'ilike', '%'.$search.'%');
        }

        $peoplesModel->with('images');
        $content['peoples'] = $peoplesModel->orderBy('people_name')->paginate(10);
        return view(
            'admin.peoples.list',
            [
                'page_title' => 'People',
                'content' => $content,
                'filterAction' => 'peoples/list/',
            ]
        );
    }

    public function anyCreate(\Illuminate\Http\Request $request)
    {
        $model = new People();
        if (Request::isMethod('post')) {
            $this->validate($request, $model->rules());
            if ($model = $model->create(Input::all())) {
                $this->anyUploadImage($model->id);
                Notification::success('People has been successfully created');
            }
            return Redirect::to(ViewHelper::adminUrlSegment() . '/peoples/list/');
        }
        return view(
            'admin.peoples.create',
            [
                'model' => $model,
                'page_title' => 'Create New People'
            ]
        );
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = People::query()->with('images')->find($id);
        if (Request::isMethod('put')) {
            $this->validate($request, $model->rules());
            $data = Input::all();
            $data['associate_verses'] = (boolean)$data['associate_verses'];
            if ($model->update($data)) {
                $this->anyUploadImage($model->id);
                Notification::success('People has been successfully updated');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to(ViewHelper::adminUrlSegment() . '/peoples/list/');
        }
        return view(
            'admin.peoples.update',
            [
                'model' => $model,
                'page_title' => 'Edit People',
            ]
        );
    }

    public function anyDelete($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $people = People::query()->with('images')->find($id);
        if ($people->images) {
            foreach ($people->images as $image) {
                $this->anyDeleteImage($image->image);
            }
        }
        if (People::destroy($id)) {
            Notification::success('People has been successfully deleted');
        }
        return ($url = Session::get('backUrl'))
            ? Redirect::to($url)
            : Redirect::to(ViewHelper::adminUrlSegment() . '/peoples/list/');
    }

    public function anyUploadImage($peopleId)
    {
        if (Input::hasFile('file')) {
            $files = Input::file('file');
            foreach ($files as $file) {
                $tmpFilePath = Config::get('app.peopleImages');
                $tmpThumbPath = $tmpFilePath . 'thumbs/';
                $tmpFileName = time() . '-' . $file->getClientOriginalName();
                $file = $file->move(public_path() . $tmpFilePath, $tmpFileName);
                $path = $tmpFilePath . $tmpFileName;

                $this->makeDir(public_path() . $tmpThumbPath);
                $thumbPath = public_path($tmpThumbPath . $tmpFileName);
                if ($file) {
                    $image = new PeopleImages();
                    $image->people_id = $peopleId;
                    $image->image = $tmpFileName;
                    $image->save();
                }
                // Resizing 200x200
                Image::make($file->getRealPath())->fit(200, 200)->save($thumbPath)->destroy();
            }
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
        if (!$filename) {
            $filename = Input::get('filename');
        }

        $image = PeopleImages::query()->where('image', $filename)->first();

        if ($image) {
            $image->delete();
        }

        $this->unlinkPeopleImage($filename);
        return response()->json(true, 200);
    }

    private function unlinkPeopleImage($filename)
    {
        $tmpFilePath = public_path(Config::get('app.peopleImages').$filename);
        $tmpThumbPath = public_path(Config::get('app.peopleImages').'thumbs/'.$filename);

        if (is_file($tmpFilePath)) {
            unlink($tmpFilePath);
        }
        if (is_file($tmpThumbPath)) {
            unlink($tmpThumbPath);
        }
    }
}
