@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('userUpdate',$model))

@section('content')
    <div class="box box-primary">
        {{--<div class="box-header with-border">
            <h3 class="box-title"></h3>
        </div>--}}
        @include('admin.user.form')
    </div>
@endsection