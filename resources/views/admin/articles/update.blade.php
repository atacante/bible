@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('articleUpdate'))

@section('content')
    <div class="box box-primary">
        @include('admin.articles.form')
    </div>
@endsection