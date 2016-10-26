@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <h2 class="h2-new cu1-h2">
                <div>
                <i class="bs-gift cu-gift2"></i>
                Gift Shop
                </div>
                <a class="btn1 cu-shop-btn1" href="{{ URL::to('/shop/cart') }}"><i class="bs-cart cu-cart2"></i> <span class="count-prod">{!! Cart::count() !!}</span> item{!! Cart::count()==1?"":"s" !!}</a>
            </h2>
        </div>
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    @include('shop.categories')
                    @include('shop.products')
                </div>

            </div>
        </div>
    </div>
@endsection
