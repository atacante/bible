@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('couponCreate'))

@section('content')
    <div class="box box-primary">
        @include('admin.complaints.form')
    </div>
@endsection