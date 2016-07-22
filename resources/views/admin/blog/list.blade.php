@extends('admin.layouts.layout')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow" style="background-color: #993C3C !important;">
                <div class="inner">
                    <h3>{!! $content['categoriesCount'] !!}</h3>
                    <p>{!! $content['categoriesCount'] != 1?'Categories':'Category' !!}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-list"></i>
                </div>
                <a href="{{ url('admin/categories/list') }}" class="small-box-footer">
                    View All <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-yellow" style="background-color: #329B44 !important;">
                <div class="inner">
                    <h3>{!! $content['articlesCount'] !!}</h3>
                    <p>{!! $content['articlesCount'] != 1?'Articles':'Article' !!}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-file-text-o"></i>
                </div>
                <a href="{{ url('admin/articles/list') }}" class="small-box-footer">
                    View All <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>
@endsection