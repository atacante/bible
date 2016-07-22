@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('categoryCreate'))

@section('content')
    <div class="box box-primary">
        @include('admin.categories.form')
    </div>
@endsection