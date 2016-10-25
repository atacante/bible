<?php

namespace App\Http\Controllers\Admin;

use App\ProductImages;
use App\ShopProduct;
use App\ShopCategory;
use App\Helpers\ViewHelper;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
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

        $content['products'] = $productModel->with(['category','images'])->orderBy('created_at', SORT_DESC)->paginate(20);
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
                $this->anyUploadImage($model->id);
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
                $this->anyUploadImage($model->id);
                Notification::success('Product has been successfully updated');
            }
            return Redirect::to(ViewHelper::adminUrlSegment() . '/shop-products/list/');
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
        $product = ShopProduct::query()->with('images')->find($id);
        if ($product->delete()) {
            if($product->images){
                foreach ($product->images as $image) {
                    $this->anyDeleteImage($image->image);
                }
            }
            Notification::success('Product has been successfully deleted');
        }
        return Redirect::to(ViewHelper::adminUrlSegment() . '/shop-products/list/');
    }

    public function anyUploadImage($productId)
    {
        if (Input::hasFile('file')) {
            $files = Input::file('file');
            foreach ($files as $file) {
                $tmpFilePath = Config::get('app.productImages');
                $tmpThumbPath = $tmpFilePath . 'thumbs/';
                $tmpFileName = time() . '-' . $file->getClientOriginalName();
                $file = $file->move(public_path() . $tmpFilePath, $tmpFileName);
                $path = $tmpFilePath . $tmpFileName;

                $this->makeDir(public_path() . $tmpThumbPath);
                $thumbPath = public_path($tmpThumbPath . $tmpFileName);
                if($file){
                    $image = new ProductImages();
                    $image->product_id = $productId;
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

        $image = ProductImages::query()->where('image', $filename)->first();

        if ($image) {
            $image->delete();
        }

        $this->unlinkLocationImage($filename);
        return response()->json(true, 200);
    }

    private function unlinkLocationImage($filename)
    {
        $tmpFilePath = public_path(Config::get('app.productImages').$filename);
        $tmpThumbPath = public_path(Config::get('app.productImages').'thumbs/'.$filename);

        if (is_file($tmpFilePath)) {
            unlink($tmpFilePath);
        }
        if (is_file($tmpThumbPath)) {
            unlink($tmpThumbPath);
        }
    }
}
