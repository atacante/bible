@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
	@parent
@stop

@section('content')
	@include('reader.filters')
    <div class="row col-md-12">
        <h3>{!! $content['heading'] !!}</h3>
    </div>
	<div class="row col-md-12" style="line-height: 30px;">
	@foreach($content['verses'] as $verse)
        <b>{!! link_to('#', $title = $verse->verse_num) !!}</b>
        {!! $verse->verse_text !!}
	@endforeach
    </div>
@stop
