<?php

namespace App\Http\Controllers\Admin;

use App\CmsPage;
use App\BlogCategory;
use App\Helpers\ViewHelper;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Krucas\Notification\Facades\Notification;


class CmsController extends Controller
{

    public function getList()
    {

        Session::flash('backUrl', Request::fullUrl());

        /*$cmsModel = BlogArticle::query();*/

        $content['cms'] = CmsPage::orderBy('created_at', SORT_DESC)->paginate(20);

        return view('admin.cms.list',
            [
                'page_title' => 'Static Pages',
                'content' => $content,
                'filterAction' => 'cms/list/',
            ]);
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = CmsPage::query()->find($id);
        if (Request::isMethod('put')){
            $this->validate($request, $model->rules());
            $data = Input::all();
            if ($model->update($data)) {
                Notification::success('CMS has been successfully updated');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to(ViewHelper::adminUrlSegment() . '/cms/list/');
        }
        return view('admin.cms.update',
            [
                'model' => $model,
                'page_title' => 'Edit CMS'
            ]);
    }
}
