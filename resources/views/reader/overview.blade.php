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
    <div class="row col-md-12 j-bible-text" style="line-height: 30px;">
        @foreach($content['versions'] as $code => $version)
            @if($version['verses']->count())
                <h4>{{ Html::link(url('reader/read?'.http_build_query(array_merge(Request::input(),['version' => $code])),[]), $version['version_name'], ['class' => '','style' => ''], true)}}</h4>
                @foreach($version['verses'] as $verse)
                    <span class="verse-text j-verse-text" data-version="{!! $code !!}" data-verseid="{!! $verse->id !!}" style="">
                    <b>{!! link_to('reader/verse?'.http_build_query([
                                                                'version' => $code,
                                                                'book' => $verse->book_id,
                                                                'chapter' => $verse->chapter_num,
                                                                'verse' => $verse->verse_num,
                                                            ]), $title = $verse->verse_num) !!}</b>&nbsp;{!! ViewHelper::prepareVerseText($verse) !!}
                </span>
                @endforeach
            @endif
        @endforeach
    </div>
@stop
