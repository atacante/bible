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
                <h2 class="h2-new mb3">
                    <i class="bs-study cu-study2"></i>
                    My Study Item
                </h2>
            </div>
        </div>
        @include('reader.entry-counters')

        @include('reader.my-notes-table')
        @include('reader.my-journal-table')
        @include('reader.my-prayers-table')

    </div>
@stop
