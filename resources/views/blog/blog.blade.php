@extends('layouts.app')

@section('content')
    <div class="row wall">
        <div class="col-xs-3">
            @include('blog.categories')
        </div>
        <div class="col-xs-9">
                <ul class="nav nav-pills tabs-nav">
                    <li class="pull-right friends-nav mb6">
                        @include('blog.search')
                    </li>
                </ul>
                <div class="clearfix"></div>
                @include('blog.articles')
        </div>
    </div>
@endsection
