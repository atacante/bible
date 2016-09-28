@extends('layouts.app')

@include('partials.cms-meta')

@section('content')
    <h2>{!! $page->title !!}</h2>
    <div class="about_content">
        {!! $page->text !!}
    </div>
@endsection
