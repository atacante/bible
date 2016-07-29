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

class ArticlesController extends Controller
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
        return view('admin.articles.list',
            [
                'page_title' => 'Articles',
                'content' => $content,
                'filterAction' => 'articles/list/',
            ]);
    }

    public function anyCreate(\Illuminate\Http\Request $request)
    {
        $model = new BlogArticle();
        $categories = BlogCategory::get()->pluck('title','id')->toArray();
        if (Request::isMethod('post')) {
            $this->validate($request, $model->rules());
            $data = Input::all();
            if ($model = $model->create($data)) {
                Notification::success('Article has been successfully created');
            }
            return Redirect::to(ViewHelper::adminUrlSegment() . '/articles/list/');
        }

        return view('admin.articles.create',
            [
                'model' => $model,
                'categories' => $categories,
                'user_id' => Auth::user()->id,
                'page_title' => 'Create New Article'
            ]);
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = BlogArticle::query()->find($id);
        $categories = BlogCategory::get()->pluck('title','id')->toArray();
        if (Request::isMethod('put')){
            $this->validate($request, $model->rules());
            $data = Input::all();
            if ($model->update($data)) {
                Notification::success('Article has been successfully updated');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to(ViewHelper::adminUrlSegment() . '/articles/list/');
        }
        return view('admin.articles.update',
            [
                'model' => $model,
                'categories' => $categories,
                'user_id' => $model->user_id,
                'page_title' => 'Edit Article'
            ]);
    }

    public function anyDelete($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $category = BlogArticle::query()->find($id);
        if ($category->delete()) {
            Notification::success('Article has been successfully deleted');
        }
        return ($url = Session::get('backUrl'))
            ? Redirect::to($url)
            : Redirect::to(ViewHelper::adminUrlSegment() . '/articles/list/');
    }
}
