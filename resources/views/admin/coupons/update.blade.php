@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('couponUpdate'))

@section('content')
    <div class="box box-primary">
        @include('admin.coupons.form')
    </div>
@endsection