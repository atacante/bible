<?php

namespace App\Http\Controllers\Admin;

use App\Order;
use App\ShopProduct;
use App\ShopCategory;
use App\Http\Requests;
use App\Http\Controllers\Controller;


class ShopController extends Controller
{
    public function getList()
    {
        $content['categoriesCount'] = ShopCategory::query()->count();
        $content['productsCount'] = ShopProduct::query()->count();
        $content['ordersCount'] = Order::query()->count();

        return view('admin.shop.list',
            [
                'page_title' => 'Shop',
                'content' => $content,
                'filterAction' => 'shop/list/',
            ]);
    }
}
