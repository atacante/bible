@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    @include('reader.filters')
    <div class="j-chapter-content">
        <div class="row" style="position: relative;">
            <h3 class="text-center">{!! $content['heading'] !!}</h3>
            <a href="#" class="j-print-chapter"><i class="fa fa-print fa-2x"
                                                   style="position: absolute; right: 0px; top: 5px; padding: 15px;"></i></a>
        </div>
        <div class="row">
            <div class="j-reader-block {!! Request::input('compare',false)?'col-md-6':"col-md-12" !!}"
                 style="line-height: 30px; text-align: justify;">
                @if(Request::input('compare',false))
                    <h4 class="text-center">{!! $content['version'] !!}</h4>
                @endif
                @foreach($content['verses'] as $verse)
                    <span style="word-wrap: normal">
                <b>{!! link_to('reader/verse?'.http_build_query([
                                                                'version' => $content['version_code'],
                                                                'book' => $verse->book_id,
                                                                'chapter' => $verse->chapter_num,
                                                                'verse' => $verse->verse_num,
                                                            ]), $title = $verse->verse_num) !!}</b>&nbsp;{!! ViewHelper::prepareVerseText($verse,true) !!}
            </span>
                @endforeach
            </div>
            @if(Request::input('compare',false))
                <div class="col-md-6 j-diff-block" style="line-height: 30px; text-align: justify;">
                    <h4 class="text-center">{!! $compare['version'] !!}
                        {!! link_to('reader/read?'.http_build_query(array_merge(Request::input(),['diff' => Request::input('diff',false)?0:1])), (Request::input('diff',false)?'hide':'show').' diff',['class' => 'btn btn-'.(Request::input('diff',false)?'danger':'success'), 'style' =>'padding: 0 5px;']) !!}
                    </h4>
                    @foreach($compare['verses'] as $verse)
                        <span style="word-wrap: normal">
                        <b>{!! link_to('reader/verse?'.http_build_query([
                                                                'version' => $compare['version_code'],
                                                                'book' => $verse->book_id,
                                                                'chapter' => $verse->chapter_num,
                                                                'verse' => $verse->verse_num,
                                                            ]), $title = $verse->verse_num) !!}</b>&nbsp;{!! ViewHelper::prepareVerseText($verse) !!}
                    </span>
                    @endforeach
                </div>
            @endif
        </div>
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
