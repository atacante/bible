@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    {{--    @include('reader.filters')--}}
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center">
                @if($prevNum = $content['pages']['prevNum'])
                    <a title="Prev Strong's Number" href="{!! url('reader/strongs-references/'.$prevNum.'/'.$content['dictionaryType'],[],false) !!}"><i class="glyphicon glyphicon-chevron-left"></i></a>
                @endif
                    Strong's {!! $content['strongNum'] !!} Occurrences
                @if($nextNum = $content['pages']['nextNum'])
                    <a title="Next Strong's Number" href="{!! url('reader/strongs-references/'.$nextNum.'/'.$content['dictionaryType'],[],false) !!}"><i class="glyphicon glyphicon-chevron-right"></i></a>
                @endif
            </h3>

        </div>
    </div>
    <div class="row strongs-page" style="line-height: 30px;">
        <div class="col-md-12">
            @include('reader.strongsreflist')
        </div>
        <div class="row">
            <div class="text-center">
                {!! $content['pagination']->links() !!}
            </div>
        </div>
    </div>
@stop
