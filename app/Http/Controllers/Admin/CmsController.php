<?php

namespace App\Http\Controllers\Admin;

use App\BlogArticle;
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
    private function prepareFilters($model)
    {
        $searchFilter = Input::get('search', false);
        $categoryFilter = Input::get('category', false);

        if (!empty($searchFilter)) {
            $model->where(function($ow) use ($searchFilter) {
                $ow->where('text', 'ilike', '%' . $searchFilter . '%');
                $ow->orWhere('title', 'ilike', '%' . $searchFilter . '%');
            });
        }

        if(!empty($categoryFilter)){
            $model->where('category_id', $categoryFilter);
        }

        return $model;
    }

    public function getList()
    {

        Session::flash('backUrl', Request::fullUrl());

        $articleModel = BlogArticle::query();

        $articleModel = $this->prepareFilters($articleModel);

        $content['articles'] = $articleModel->with(['user','category'])->orderBy('published_at', SORT_DESC)->paginate(20);

        return view('admin.cms.list',
            [
                'page_title' => 'Static Pages',
                'content' => $content,
                'filterAction' => 'cms/list/',
            ]);
    }
}
