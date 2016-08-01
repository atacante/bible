<?php

namespace App\Http\Controllers\Admin;

use App\ShopProduct;
use App\ShopCategory;
use App\Helpers\ViewHelper;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Krucas\Notification\Facades\Notification;

class ShopProductsController extends Controller
{

    private function prepareFilters($model)
    {
        $searchFilter = Input::get('search', false);
        $categoryFilter = Input::get('category', false);

        if (!empty($searchFilter)) {
            $model->where(function($ow) use ($searchFilter) {
                $ow->where('name', 'ilike', '%' . $searchFilter . '%');
                $ow->orWhere('short_description', 'ilike', '%' . $searchFilter . '%');
                $ow->orWhere('long_description', 'ilike', '%' . $searchFilter . '%');
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

        $productModel = ShopProduct::query();

        $productModel = $this->prepareFilters($productModel);

        $content['products'] = $productModel->with('category')->orderBy('created_at', SORT_DESC)->paginate(20);
        return view('admin.shop.products.list',
            [
                'page_title' => 'Products',
                'content' => $content,
                'filterAction' => 'shop-products/list/',
            ]);
    }

    public function anyCreate(\Illuminate\Http\Request $request)
    {
        $model = new ShopProduct();
        $categories = ShopCategory::get()->pluck('title','id')->toArray();
        if (Request::isMethod('post')) {
            $this->validate($request, $model->rules());
            $data = Input::all();
            if ($model = $model->create($data)) {
                Notification::success('Product has been successfully created');
            }
            return Redirect::to(ViewHelper::adminUrlSegment() . '/shop-products/list/');
        }

        return view('admin.shop.products.create',
            [
                'model' => $model,
                'categories' => $categories,
                'user_id' => Auth::user()->id,
                'page_title' => 'Create New Product'
            ]);
    }

    public function anyUpdate(\Illuminate\Http\Request $request, $id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $model = ShopProduct::query()->find($id);
        $categories = ShopCategory::get()->pluck('title','id')->toArray();
        if (Request::isMethod('put')){
            $this->validate($request, $model->rules());
            $data = Input::all();
            if ($model->update($data)) {
                Notification::success('Product has been successfully updated');
            }
            return ($url = Session::get('backUrl'))
                ? Redirect::to($url)
                : Redirect::to(ViewHelper::adminUrlSegment() . '/shop-products/list/');
        }
        return view('admin.shop.products.update',
            [
                'model' => $model,
                'categories' => $categories,
                'user_id' => $model->user_id,
                'page_title' => 'Edit Product'
            ]);
    }

    public function anyDelete($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }
        $category = ShopProduct::query()->find($id);
        if ($category->delete()) {
            Notification::success('Product has been successfully deleted');
        }
        return ($url = Session::get('backUrl'))
            ? Redirect::to($url)
            : Redirect::to(ViewHelper::adminUrlSegment() . '/shop-products/list/');
    }
}
