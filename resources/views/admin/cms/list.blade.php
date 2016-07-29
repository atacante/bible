@extends('admin.layouts.layout')

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            {{--<div class="small-box bg-yellow" style="background-color: #d1c724 !important;">
                <div class="inner">
                    <h3>{!! $content['articlesCount'] !!}</h3>
                    <p>{!! $content['articlesCount'] != 1?'Static pages':'Static page' !!}</p>
                </div>
                <div class="icon">
                    <i class="fa fa-newspaper-o"></i>
                </div>
                <a href="{{ url('admin/articles/list') }}" class="small-box-footer">
                    View All <i class="fa fa-arrow-circle-right"></i>
                </a>
            </div>--}}
        </div>
    </div>
@endsection