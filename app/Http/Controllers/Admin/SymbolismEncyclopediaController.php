<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ViewHelper;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\SymbolismEncyclopedia;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Krucas\Notification\Facades\Notification;

class SymbolismEncyclopediaController extends Controller
{
    public function getList()
    {
        Session::flash('backUrl', Request::fullUrl());

        $termsModel = SymbolismEncyclopedia::query();

        if(!empty($search = Request::input('search', false))){
            $termsModel->where('term_name', 'ilike', '%'.$search.'%');
            $termsModel->orWhere('term_description', 'ilike', '%'.$search.'%');
        }

        $content['terms'] = $termsModel->orderBy('term_name')->paginate(10);
        return view('admin.symbolism-encyclopedia.list',
            [
                'page_title' => 'Terms',
                'content' => $content,
                'filterAction' => 'symbolism-encyclopedia/list/',
            ]);
    }

    public function anyCreate(\Illuminate\Http\Request $request)
    {
        $model = new SymbolismEncyclopedia();
        if (Request::isMethod('post')) {
            $this->validate($request, $model->rules());
            $data = Input::all();
            $data['associate_lexicons'] = (boolean)$data['associate_lexicons'];
            if ($model = $model->create($data)) {
                Notification::success('Term has been successfully created');
            }
            return Redirect::to(ViewHelper::adminUrlSegment() . '/symbolism-encyclopedia/list/');
        }
        return view('admin.symbolism-encyclopedia.create',
            [
                'model' => $model,
                'page_title' => 'Create New Term'
            ]);
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = SymbolismEncyclopedia::query()->find($id);
        if (Request::isMethod('put')) {
            $this->validate($request, $model->rules());
            $data = Input::all();
            $data['associate_lexicons'] = (boolean)$data['associate_lexicons'];
            if ($model->update($data)) {
                Notification::success('Term has been successfully updated');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to(ViewHelper::adminUrlSegment() . '/symbolism-encyclopedia/list/');
        }
        return view('admin.symbolism-encyclopedia.update',
            [
                'model' => $model,
                'page_title' => 'Edit Term',
            ]);
    }

    public function anyDelete($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = SymbolismEncyclopedia::query()->find($id);
        if (SymbolismEncyclopedia::destroy($id)) {
            Notification::success('Term has been successfully deleted');
        }
        return ($url = Session::get('backUrl'))
            ? Redirect::to($url)
            : Redirect::to(ViewHelper::adminUrlSegment() . '/symbolism-encyclopedia/list/');
    }
}
