@extends('layouts.app')

@section('content')
    <div class="row wall">
        <div class="col-md-2">
            @include('blog.categories')
        </div>
        <div class="col-md-10 related-records public-wall">
            @include('blog.articles')
        </div>
    </div>
@endsection
