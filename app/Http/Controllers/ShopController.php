<?php namespace App\Http\Controllers;

use App\ShopProduct;
use App\ShopCategory;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;
use Krucas\Notification\Facades\Notification;

class ShopController extends Controller
{


    private function prepareFilters($model)
    {
        $searchFilter = Input::get('search', false);
        $categoryFilter = Input::get('category', false);

        if (!empty($searchFilter)) {
            $model->where(function ($ow) use ($searchFilter) {
                $ow->where('name', 'ilike', '%' . $searchFilter . '%');
                $ow->orWhere('short_description', 'ilike', '%' . $searchFilter . '%');
                $ow->orWhere('long_description', 'ilike', '%' . $searchFilter . '%');
            });
        }

        if (!empty($categoryFilter)) {
            $model->where('category_id', $categoryFilter);
        }

        return $model;
    }

    /**
     * Display a listing of the resource.
     * GET /blog
     *
     * @return Response
     */
    public function getIndex()
    {
        Session::flash('backUrl', Request::fullUrl());

        $categories =  ShopCategory::all();
        $products =  ShopProduct::query();

        $products = $this->prepareFilters($products);

        $products = $products->orderBy('created_at', SORT_DESC)->paginate(10);

        return view('shop.shop', ['categories'=>$categories, 'products'=>$products]);
    }

    /**
     * Display a view of the .
     * GET /blog/article/{id}
     *
     * @return Response
     */
    public function getProduct($id)
    {
        if (Session::has('backUrl')) {
            Session::keep('backUrl');
        }

        $product =  ShopProduct::find($id);

        if (!$product) {
            Notification::error('This item was not found');
            return $this->redirectToBackUrl();
        }

        return view('shop.product_view', ['product'=>$product]);
    }

    /**
     * Display a view of the cart
     * GET /shop/cart
     *
     * @return Response
     */
    public function getCart()
    {
        return view('shop.cart');
    }

    /**
     * Add products to the cart
     * GET /shop/add-to-cart/{id}
     *
     * @return Response
     */
    public function getAddToCart($id)
    {
        $product = ShopProduct::find($id);
        $color = Request::input('color', 'default');
        $size = Request::input('size', 'default');

        if (!$product) {
            Notification::error('This item was not found');
            return $this->redirectToBackUrl();
        }

        Cart::add($product, 1, ['color'=>$color, 'size'=>$size]);//->setTaxRate('10');
        return Redirect::to('/shop/cart');
    }

    /**
     * Display a view of the cart
     * POST /shop/cart-update/{id}
     *
     * @return Response
     */
    public function postCartUpdate(Request $request)
    {
        $rowId = Input::get('rowId');
        $qty = Input::get('qty');

        Cart::update($rowId, $qty);
        return Redirect::to('/shop/cart');
    }

    /**
     * Display a view of the cart
     * GET /shop/add-to-cart/{id}
     *
     * @return Response
     */
    public function getCartDelete($rowId)
    {
        Cart::remove($rowId);
        return Redirect::to('/shop/cart');
    }
}
