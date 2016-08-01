@extends('admin.layouts.layout')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow" style="background-color: #993C3C !important;">
                <div class="inner">
                    <h3>{!! $content['categoriesCount'] !!}</h3>
                    <p>{!! $content['categoriesCount'] != 1?'Shop Categories':'Shop Category' !!}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-list-alt"></i>
                </div>
                <a href="{{ url('admin/shop-categories/list') }}" class="small-box-footer">
                    View All <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow" style="background-color: #329B44 !important;">
                <div class="inner">
                    <h3>{!! $content['productsCount'] !!}</h3>
                    <p>{!! $content['productsCount'] != 1?'Shop Products':'Shop Product' !!}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <a href="{{ url('admin/shop-products/list') }}" class="small-box-footer">
                    View All <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
@endsection