@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    <div class="j-my-study-item my-study-item">
        {!! Form::hidden('rel',Request::get('rel',null)) !!}
        <div class="row text-center" style="line-height: 30px; margin-bottom: 15px;">
            <div class="col-md-12">
                <span>
                    <h3 class="text-center">My Study Item</h3>
                </span>
                @include('reader.entry-stars')
            </div>
        </div>
        @include('reader.entry-counters')

        @include('reader.my-notes-table')
        @include('reader.my-journal-table')
        @include('reader.my-prayers-table')

    </div>
@stop
