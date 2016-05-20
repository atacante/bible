@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('reader.filters')
        </div>
    </div>
    <div class="row">
        <div class="col-md-{!! !$content['relatedItems']->count() || Request::input('compare',false)?'12':'9' !!}">
            <div class="j-chapter-content">
                <div class="row" style="position: relative;">
                    <h3 class="text-center" style="margin: 30px auto 20px;">
                        <div class="btn-group" role="group" aria-label="...">
                            {{--@if($prevBook = $content['pagination']['bookPrev'])--}}
                            {{--{{ Html::link(url('reader/read?'.http_build_query($prevBook),[],false), 'Prev Book', ['class' => 'btn btn-default btn-danger','style' => 'padding: 2px 5px;'], true)}}--}}
                            {{--@endif--}}
                            @if($prevChapter = $content['pagination']['chapterPrev'])
                                <a title="Prev Chapter"
                                   href="{!! url('reader/read?'.http_build_query($prevChapter),[],false) !!}"><i
                                            class="glyphicon glyphicon-chevron-left"></i></a>
                                {{--                        {{ Html::link(url('reader/read?'.http_build_query($prevChapter),[],false), 'Prev Chapter', ['class' => 'btn btn-default btn-danger','style' => 'padding: 2px 5px;'], true)}}--}}
                            @endif
                        </div>
                        {!! $content['heading'] !!}
                        @if(Request::input('compare',false))
                            {!! link_to('reader/read?'.http_build_query(array_merge(Request::input(),['diff' => Request::input('diff',false)?0:1])), (Request::input('diff',false)?'hide':'show').' diff',['class' => 'btn btn-'.(Request::input('diff',false)?'danger':'success'), 'style' =>'padding: 0 5px;']) !!}
                        @endif
                        <div class="btn-group" role="group" aria-label="...">
                            @if($nextChapter = $content['pagination']['chapterNext'])
                                <a title="Next Chapter"
                                   href="{!! url('reader/read?'.http_build_query($nextChapter),[],false) !!}"><i
                                            class="glyphicon glyphicon-chevron-right"></i></a>
                                {{--                        {{ Html::link(url('reader/read?'.http_build_query($nextChapter),[],false), 'Next Chapter', ['class' => 'btn btn-default btn-primary','style' => 'padding: 2px 5px;'], true)}}--}}
                            @endif
                            {{--@if($nextBook = $content['pagination']['bookNext'])--}}
                            {{--{{ Html::link(url('reader/read?'.http_build_query($nextBook),[],false), 'Next Book', ['class' => 'btn btn-default btn-primary','style' => 'padding: 2px 5px;'], true)}}--}}
                            {{--@endif--}}
                        </div>
                    </h3>
                    <a href="#" class="j-print-chapter"><i class="fa fa-print fa-2x"
                                                           style="position: absolute; right: 0px; top: 5px; padding: 15px;"></i></a>
                </div>
                <div class="row">
                    <div class="j-reader-block j-bible-text {!! (Request::input('compare',false)?count(Request::input('compare',false)) == 1?'col-md-6':'col-md-4':"col-md-12") !!}"
                         style="line-height: 30px; text-align: justify;">
                        @if(Request::input('compare',false))
                            <h4 class="text-center">{!! $content['version'] !!}</h4>
                        @endif
                        @foreach($content['verses'] as $verse)
                            <span class="j-verse-text" data-version="{!! $content['version_code'] !!}"
                                  data-verseid="{!! $verse->id !!}" style="word-wrap: normal">
                <b>{!! link_to('reader/verse?'.http_build_query([
                                                                'version' => $content['version_code'],
                                                                'book' => $verse->book_id,
                                                                'chapter' => $verse->chapter_num,
                                                                'verse' => $verse->verse_num,
                                                            ]), $title = $verse->verse_num) !!}</b>&nbsp;{!! ViewHelper::prepareVerseText($verse,true) !!}
            </span>
                        @endforeach
                    </div>
                    @if($compareParam = Request::input('compare',false))
                        @foreach($compare['data'] as $version)
                            <div class="col-md-{!! count($compareParam) == 1?'6':'4' !!} j-diff-block j-bible-text"
                                 data-version="{!! $version['version_code'] !!}"
                                 style="line-height: 30px; text-align: justify;">
                                <h4 class="text-center">
                                    {!! $version['version'] !!}
                                    {{--                            {!! link_to('reader/read?'.http_build_query(array_merge(Request::input(),['diff' => Request::input('diff',false)?0:1])), (Request::input('diff',false)?'hide':'show').' diff',['class' => 'btn btn-'.(Request::input('diff',false)?'danger':'success'), 'style' =>'padding: 0 5px;']) !!}--}}
                                </h4>
                                @foreach($version['verses'] as $verse)
                                    <span class="j-verse-text" data-verseid="{!! $verse->id !!}"
                                          style="word-wrap: normal">
                        <b>{!! link_to('reader/verse?'.http_build_query([
                                                                'version' => $version['version_code'],
                                                                'book' => $verse->book_id,
                                                                'chapter' => $verse->chapter_num,
                                                                'verse' => $verse->verse_num,
                                                            ]), $title = $verse->verse_num) !!}</b>&nbsp;{!! ViewHelper::prepareVerseText($verse) !!}
                        </span>
                                @endforeach
                            </div>
                        @endforeach
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
        </div>
        @if($content['relatedItems']->count() || !Request::input('compare',false))
            <div class="col-md-3">
                <h3 class="text-center" style="margin: 30px auto 30px;">Related Records</h3>
                @foreach($content['relatedItems'] as $item)
                    <div class="related-item">
                        <div class="item-header">
                            <i title="{!! ucfirst($item->type) !!} Entry"
                               class="pull-left fa fa-btn {!! ViewHelper::getRelatedItemIcon($item->type) !!}"></i>
                            <div class="pull-left">
                                {!! ViewHelper::getVerseNum($item->verse) !!}
                            </div>
                            <div class="pull-right">{!! $item->created_at->format('m/d/Y') !!}</div>
                        </div>
                        <div class="item-body j-item-body" data-itemid="{!! $item->id !!}" , data-itemtype="{!! $item->type !!}">
                                @if($item->highlighted_text)
                                <div class="verse-block">
                                    Verse: <i>{!! $item->highlighted_text !!}</i>
                                </div>
                                @endif
                            {!! str_limit(strip_tags($item->text,'<p></p>'), $limit = 100, $end = '...') !!}
                        </div>
                    </div>
                @endforeach
                {{--<div class="related-item">
                    <div class="item-header">
                        <i title="Journal Entry" class="pull-left fa fa-btn fa-book"></i>
                        <div class="pull-left">Journal Entry at 05/19/2016</div>
                    </div>
                    <div class="item-body"></div>
                </div>
                <div class="related-item">
                    <div class="item-header">
                        <i title="Prayer Entry" class="pull-left fa fa-btn fa-hand-paper-o"></i>
                        <div class="pull-left">Prayer Entry at 05/19/2016</div>
                    </div>
                    <div class="item-body"></div>
                </div>--}}
            </div>
        @endif
    </div>
@stop
