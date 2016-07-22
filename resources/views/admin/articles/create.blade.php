@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('articleCreate'))

@section('content')
    <div class="box box-primary">
        @include('admin.articles.form')
    </div>
@endsection