@extends('admin.layouts.layout')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{!! $content['lexiconsCount'] !!}</h3>

                    <p>Lexicon{!! $content['lexiconsCount'] != 1?'s':'' !!}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-university"></i>
                </div>
                <a href="{{ url('admin/lexicon/list') }}" class="small-box-footer">
                    View All <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{!! $content['bibleVersionsCount'] !!}</h3>
                    <p>Bible Version{!! $content['bibleVersionsCount'] != 1?'s':'' !!}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-book"></i>
                </div>
                <a href="{{ url('admin/bible/versions') }}" class="small-box-footer">
                    View All <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{!! $content['totalUsersCount'] !!}</h3>
                    <p>User{!! $content['totalUsersCount'] != 1?'s':'' !!}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <a href="{{ url('admin/user/list') }}" class="small-box-footer">
                    View All <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{!! $content['locationsCount'] !!}</h3>
                    <p>Location{!! $content['locationsCount'] != 1?'s':'' !!}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-location"></i>
                </div>
                <a href="{{ url('admin/location/list') }}" class="small-box-footer">
                    View All <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <!-- ./col -->
        {{--<div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>65</h3>

                    <p>Unique Visitors</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>--}}
                <!-- ./col -->
    </div>
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red" style="background-color: #e44ad7 !important;">
                <div class="inner">
                    <h3>{!! $content['peoplesCount'] !!}</h3>
                    <p>People{{--{!! $content['peoplesCount'] != 1?'s':'' !!}--}}</p>
                </div>
                <div class="icon">
                    <i class="ion ion-ios-people"></i>
                </div>
                <a href="{{ url('admin/peoples/list') }}" class="small-box-footer">
                    View All <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-red" style="background-color: #337ab7 !important;">
                <div class="inner">
                    <h3>{!! $content['couponsCount'] !!}</h3>
                    <p>Coupon{!! $content['couponsCount'] != 1?'s':'' !!}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-ticket"></i>
                </div>
                <a href="{{ url('admin/coupons/list') }}" class="small-box-footer">
                    View All <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow" style="background-color: #737357 !important;">
                <div class="inner">
                    <h3>CMS</h3>
                    <p>&nbsp;</p>
                </div>
                <div class="icon">
                    <i class="fa fa-newspaper-o"></i>
                </div>
                <a href="{{ url('admin/cms') }}" class="small-box-footer">
                    View All <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow" style="background-color: #00736e !important;">
                <div class="inner">
                    <h3>Shop</h3>
                    <p>&nbsp;</p>
                </div>
                <div class="icon">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <a href="{{ url('admin/shop/list') }}" class="small-box-footer">
                    View All <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow" style="background-color: #734c1a !important;">
                <div class="inner">
                    <h3 style="font-size: 24px; line-height: 42px;">Subscriptions</h3>
                    <p>&nbsp;</p>
                </div>
                <div class="icon">
                    <i class="fa ion-at"></i>
                </div>
                <a href="{{ url('admin/subscription/list') }}" class="small-box-footer">
                    View All <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow" style="background-color: #dd3950 !important;">
                <div class="inner">
                    <h3 style="font-size: 24px; line-height: 42px;">Complaints</h3>
                    <p>&nbsp;</p>
                </div>
                <div class="icon">
                    <i class="fa fa-btn fa-flag"></i>
                </div>
                <a href="{{ url('admin/complaints/list') }}" class="small-box-footer">
                    View All <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow" style="background-color: #007f44 !important;">
                <div class="inner">
                    <h3 style="font-size: 24px; line-height: 42px;">Reports</h3>
                    <p>&nbsp;</p>
                </div>
                <div class="icon">
                    <i class="fa fa-pie-chart"></i>
                </div>
                <a href="{{ url('admin/reports') }}" class="small-box-footer">
                    View All <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
@endsection