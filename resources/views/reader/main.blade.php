@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    @include('reader.filters')
    <div class="row col-md-12">
        <h3 class="text-center">{!! $content['heading'] !!}</h3>
    </div>
    <div class="row col-md-12" style="line-height: 30px;">
        @foreach($content['verses'] as $verse)
            <b>{!! link_to('#', $title = $verse->verse_num) !!}</b>
            {!! $verse->verse_text !!}
        @endforeach
    </div>
    <div class="row col-md-12 pagination" style="text-align: center;">
        <div class="btn-group" role="group" aria-label="...">
            @if($prevBook = $content['pagination']['bookPrev'])
                {{ Html::link(url('reader/read?'.http_build_query($prevBook),[],false), 'Prev Book', ['class' => 'btn btn-default btn-danger','style' => 'width:120px;'], true)}}
            @endif
            @if($prevChapter = $content['pagination']['chapterPrev'])
                {{ Html::link(url('reader/read?'.http_build_query($prevChapter),[],false), 'Prev Chapter', ['class' => 'btn btn-default btn-danger','style' => 'width:120px;'], true)}}
            @endif
            @if($nextChapter = $content['pagination']['chapterNext'])
                {{ Html::link(url('reader/read?'.http_build_query($nextChapter),[],false), 'Next Chapter', ['class' => 'btn btn-default btn-primary','style' => 'width:120px;'], true)}}
            @endif
            @if($nextBook = $content['pagination']['bookNext'])
                {{ Html::link(url('reader/read?'.http_build_query($nextBook),[],false), 'Next Book', ['class' => 'btn btn-default btn-primary','style' => 'width:120px;'], true)}}
            @endif
        </div>
    </div>
@stop
