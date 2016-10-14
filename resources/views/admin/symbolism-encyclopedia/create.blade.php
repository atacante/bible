@extends('admin.layouts.layout')

@section('breadcrumbs', Breadcrumbs::render('termCreate'))

@section('content')
    <div class="box box-primary">
        @include('admin.symbolism-encyclopedia.form')
    </div>
@endsection