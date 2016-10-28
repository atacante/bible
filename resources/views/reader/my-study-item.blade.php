@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    <div class="j-my-study-item my-study-item bl-my-study-verse">
        {!! Form::hidden('rel',Request::get('rel',null)) !!}
        <h2 class="bl-heading">
            <i class="bs-study cu-study2"></i>My Study Item
        </h2>
        @include('reader.entry-counters')

        @include('reader.my-notes-table')
        @include('reader.my-journal-table')
        @include('reader.my-prayers-table')

    </div>
@stop
