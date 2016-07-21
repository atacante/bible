@extends('layouts.app')

@section('content')
    <div class="row wall">
        <div class="col-md-2">
            @include('blog.categories')
        </div>
        <div class="col-md-10 related-records public-wall">
            <div class="row">
                <div class="pull-right friends-nav">
                    @include('blog.search')
                </div>
            </div>
            <div class="row">
                @include('blog.articles')
            </div>
        </div>
    </div>
@endsection
