<?php namespace App\Http\Controllers;

use App\ShopProduct;
use App\ShopCategory;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class ShopController extends Controller {


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

	/**
	 * Display a listing of the resource.
	 * GET /blog
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$categories =  ShopCategory::all();
		$products =  ShopProduct::query();

		$products = $this->prepareFilters($products);

		$products = $products->orderBy('created_at', SORT_DESC)->paginate(10);

		return view('shop.shop',['categories'=>$categories, 'products'=>$products]);
	}

	/**
	 * Display a view of the .
	 * GET /blog/article/{id}
	 *
	 * @return Response
	 */
	public function getProduct($id)
	{
		$product =  ShopProduct::find($id);

		return view('shop.product_view',['product'=>$product]);
	}

    /**
	 * Display a view of the cart
	 * GET /shop/add-to-cart/{id}
	 *
	 * @return Response
	 */
	public function getAddToCart($id)
	{
		$product =  ShopProduct::find($id);
        Cart::add($product, 1);
		return view('shop.cart');
	}

    /**
	 * Display a view of the cart
	 * GET /shop/add-to-cart/{id}
	 *
	 * @return Response
	 */
	public function getCartUpdate($rowId)
	{
        Cart::update($rowId, 1);
		return view('shop.cart');
	}

}