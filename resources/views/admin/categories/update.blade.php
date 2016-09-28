@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('categoryUpdate'))

@section('content')
    <div class="box box-primary">
        @include('admin.categories.form')
    </div>
@endsection