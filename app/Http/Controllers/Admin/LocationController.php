<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ViewHelper;
use App\Location;
//use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Kodeine\Acl\Models\Eloquent\Role;
use Krucas\Notification\Facades\Notification;

class LocationController extends Controller
{
    public function getList()
    {
        Session::flash('backUrl', Request::fullUrl());

        $book = Request::input('book', false);
        $chapter = Request::input('chapter', false);
        $verse = Request::input('verse', false);

        $locations = Location::query()->with('booksListEn');

        if(!empty($book)){
            $locations->where('book_id',$book);
        }

        if(!empty($chapter)){
            $locations->where('chapter_num',$chapter);
        }

        if(!empty($verse)) {
            $locations->where('verse_num', $verse);
        }

        $content['locations'] = $locations->/*orderBy('book_id')->orderBy('chapter_num')->orderBy('verse_num')->*/paginate(20);
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
        if (Request::isMethod('post')) {
            $this->validate($request,$model->rules());
            if($model->create(Input::all())){
                Notification::success('Location has been successfully created');
            }
            return Redirect::to(ViewHelper::adminUrlSegment().'/location/list/');
        }
        return view('admin.location.create',
            [
                'model' => $model,
                'page_title' => 'Create New Location'
            ]);
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = Location::query()->find($id);
        if (Request::isMethod('put')) {
            $this->validate($request,$model->rules());
            if($model->update(Input::all())){
                Notification::success('Location has been successfully updated');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to(ViewHelper::adminUrlSegment().'/location/list/');
        }
        return view('admin.location.update',
            [
                'model' => $model,
                'page_title' => 'Edit Location',
            ]);
    }

    public function anyDelete($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        if(Location::destroy($id)){
            Notification::success('Location has been successfully deleted');
        }
        return ($url = Session::get('backUrl'))
            ? Redirect::to($url)
            : Redirect::to(ViewHelper::adminUrlSegment().'/user/list/');
    }
}
