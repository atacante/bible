@extends('layouts.app')

@section('content')
    <div class="row wall">
        <div class="col-xs-3">
            @include('blog.categories')
        </div>
        <div class="col-xs-9">
                <div class="pull-right friends-nav">
                    @include('blog.search')
                </div>
                <div class="clearfix"></div>
                @include('blog.articles')
        </div>
    </div>
@endsection
