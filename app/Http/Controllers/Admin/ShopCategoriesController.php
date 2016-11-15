<?php

namespace App\Http\Controllers\Admin;

use App\ShopCategory;
use App\Helpers\ViewHelper;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Krucas\Notification\Facades\Notification;

class ShopCategoriesController extends Controller
{
    private function prepareFilters($model)
    {
        $searchFilter = Input::get('search', false);

        if (!empty($searchFilter)) {
            $model->where('title', 'ilike', '%' . $searchFilter . '%');
        }

        return $model;
    }

    public function getList()
    {
        Session::flash('backUrl', Request::fullUrl());

        $categoryModel = ShopCategory::query();

        $categoryModel = $this->prepareFilters($categoryModel);

        $content['categories'] = $categoryModel->orderBy('id')->paginate(20);
        return view(
            'admin.shop.categories.list',
            [
                'page_title' => 'Categories',
                'content' => $content,
                'filterAction' => 'shop-categories/list/',
            ]
        );
    }

    public function anyCreate(\Illuminate\Http\Request $request)
    {
        $model = new ShopCategory();
        if (Request::isMethod('post')) {
            $this->validate($request, $model->rules());
            $data = Input::all();
            if ($model = $model->create($data)) {
                Notification::success('Category has been successfully created');
            }
            return Redirect::to(ViewHelper::adminUrlSegment() . '/shop-categories/list/');
        }

        return view(
            'admin.shop.categories.create',
            [
                'model' => $model,
                'page_title' => 'Create New Category'
            ]
        );
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = ShopCategory::query()->find($id);
        if (Request::isMethod('put')) {
            $this->validate($request, $model->rules());
            $data = Input::all();
            if ($model->update($data)) {
                Notification::success('Category has been successfully updated');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to(ViewHelper::adminUrlSegment() . '/shop-categories/list/');
        }
        return view(
            'admin.shop.categories.update',
            [
                'model' => $model,
                'page_title' => 'Edit Category'
            ]
        );
    }

    public function anyDelete($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $category = ShopCategory::query()->find($id);
        if ($category->delete()) {
            Notification::success('Category has been successfully deleted');
        }
        return ($url = Session::get('backUrl'))
            ? Redirect::to($url)
            : Redirect::to(ViewHelper::adminUrlSegment() . '/shop-categories/list/');
    }
}
