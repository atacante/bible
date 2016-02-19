@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    @include('reader.filters')
    <div class="row col-md-12">
        <h3 class="text-center">{!! $content['heading'] !!} - Versions Overview</h3>
    </div>
    <div class="row col-md-12" style="line-height: 30px;">
        @foreach($content['versions'] as $code => $version)
            <h4>{{ Html::link(url('reader/read?'.http_build_query(array_merge(Request::input(),['version' => $code])),[],false), $version['version_name'], ['class' => '','style' => ''], true)}}</h4>
            @foreach($version['verses'] as $verse)
                <span style="">
                    <b>{!! link_to('#', $title = $verse->verse_num) !!}</b>&nbsp;{!! $verse->verse_text !!}
                </span>
            @endforeach
        @endforeach
    </div>
@stop
