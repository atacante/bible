@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('productCreate'))

@section('content')
    <div class="box box-primary">
        @include('admin.shop.products.form')
    </div>
@endsection