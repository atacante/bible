@extends('layouts.app')

@section('content')
    <div class="row wall">
        <div class="col-md-3">
            @include('blog.categories')
        </div>
        <div class="col-md-9">
            <div class="resp-text-center">
                <ul class="nav nav-pills tabs-nav">
                    <li class="pull-left friends-nav mb6">
                        <h1 class="blog-cat-title">
                            {!! Request::get('category',false)?$categories->where('id',(int)Request::get('category'))->first()->title:'All Categories' !!}
                        </h1>
                    </li>
                    <li class="pull-right friends-nav mb6">
                        @include('blog.search')
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
            @include('blog.articles')
        </div>
    </div>
@endsection
