@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('termUpdate'))

@section('content')
    <div class="box box-primary">
        @include('admin.symbolism-encyclopedia.form')
    </div>
@endsection