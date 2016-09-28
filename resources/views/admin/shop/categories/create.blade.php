@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('shop-categoryCreate'))

@section('content')
    <div class="box box-primary">
        @include('admin.shop.categories.form')
    </div>
@endsection