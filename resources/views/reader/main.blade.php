@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    {!! $content['heading'] !!}
@stop

@section('meta_description')
    <meta name="description" content="{{$content['verses'][0]->verse_text}}"/>
@stop

@section('meta_twitter')
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="{!! $content['heading'] !!}">
    <meta property="twitter:description" content="{{$content['verses'][0]->verse_text}}">
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            @include('reader.filters')
        </div>
    </div>
    <div class="row">
        <div class="col-md-{!! !$content['relatedItems']->count() || Request::input('compare',false)?'12':'9' !!}">
            <div class="j-chapter-content ">
                <div class="row" style="position: relative;">
                    <div class="col-lg-12">
                        <div class="c-title-and-icons">
                            <!-- Go to www.addthis.com/dashboard to customize your tools -->
                            <div class="addthis_sharing_toolbox c-sharing"></div>

                            <h3 class="text-center">
                                <div class="btn-group" role="group" aria-label="...">
                                    @if($prevChapter = $content['pagination']['chapterPrev'])
                                        <a title="Prev Chapter" href="{!! url('reader/read?'.http_build_query($prevChapter),[],false) !!}"><i class="glyphicon glyphicon-chevron-left"></i></a>
                                    @endif
                                </div>
                                {!! $content['heading'] !!}
                                @if(Request::input('compare',false))
                                    {!! link_to('reader/read?'.http_build_query(array_merge(Request::input(),['diff' => Request::input('diff',false)?0:1])), (Request::input('diff',false)?'hide':'show').' diff',['class' => 'btn btn-'.(Request::input('diff',false)?'danger':'success'), 'style' =>'padding: 0 5px;']) !!}
                                @endif
                                <div class="btn-group" role="group" aria-label="...">
                                    @if($nextChapter = $content['pagination']['chapterNext'])
                                        <a title="Next Chapter" href="{!! url('reader/read?'.http_build_query($nextChapter),[],false) !!}"><i class="glyphicon glyphicon-chevron-right"></i></a>
                                    @endif
                                </div>
                            </h3>

                            <a href="#" class="j-print-chapter c-print-chapter"><i class="bs-print cu-print"></i></a>
                        </div>

                    </div>
                </div>




                <div class="row">
                    <div class="col-lg-12">
                        <div class="c-reader-content j-reader-block j-bible-text {!! (Request::input('compare',false)?count(Request::input('compare',false)) == 1?'col-md-6':'col-md-4':"col-md-12") !!}" style="line-height: 30px; text-align: justify;">
                            @if(Request::input('compare',false))
                                <h4 class="text-center">{!! $content['version'] !!}</h4>
                            @endif
                            @foreach($content['verses'] as $verse)
                                <span class="verse-text j-verse-text" data-version="{!! $content['version_code'] !!}"
                                      data-verseid="{!! $verse->id !!}" style="word-wrap: normal">
                                        <b>{!! link_to('reader/verse?'.http_build_query([
                                                                    'version' => $content['version_code'],
                                                                    'book' => $verse->book_id,
                                                                    'chapter' => $verse->chapter_num,
                                                                    'verse' => $verse->verse_num,
                                                                ]), $title = $verse->verse_num) !!}
                                        </b>&nbsp;{!! ViewHelper::prepareVerseText($verse,true) !!}
                                </span>
                            @endforeach
                        </div>
                    </div>
                    @if($compareParam = Request::input('compare',false))
                        @foreach($compare['data'] as $version)
                            <div class="col-md-{!! count($compareParam) == 1?'6':'4' !!} j-diff-block j-bible-text"
                                 data-version="{!! $version['version_code'] !!}"
                                 style="line-height: 30px; text-align: justify;">
                                <h4 class="text-center">
                                    {!! $version['version'] !!}
                                </h4>
                                @foreach($version['verses'] as $verse)
                                    <span class="verse-text j-verse-text" data-verseid="{!! $verse->id !!}" style="word-wrap: normal">
                                        <b>{!! link_to('reader/verse?'.http_build_query([
                                                                'version' => $version['version_code'],
                                                                'book' => $verse->book_id,
                                                                'chapter' => $verse->chapter_num,
                                                                'verse' => $verse->verse_num,
                                                            ]), $title = $verse->verse_num) !!}
                                        </b>&nbsp;{!! ViewHelper::prepareVerseText($verse) !!}
                                    </span>
                                @endforeach
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- ---------------- Pagination  ---------------- --}}
            <div class="row mt14">
                <div class="col-md-12">
                    <div class="pull-left">
                        @if($prevBook = $content['pagination']['bookPrev'])
                            <a href="{!! url('reader/read?'.http_build_query($prevBook),[],false) !!}" class="btn2 mr5 btn-min-w">
                                <div class="btn-top-label1">Prev Book</div>
                                <div class="btn-sub-label1">Leviticus</div>
                            </a>
                        @endif
                        @if($prevChapter = $content['pagination']['chapterPrev'])
                            <a href="{!! url('reader/read?'.http_build_query($prevChapter),[],false) !!}" class="btn2 mr5 btn-min-w">
                                <div class="btn-top-label1">Prev Chapter</div>
                                <div class="btn-sub-label1">Genesis 9</div>
                            </a>
                        @endif
                    </div>
                    <div class="pull-right">
                        @if($nextChapter = $content['pagination']['chapterNext'])
                            <a href="{!! url('reader/read?'.http_build_query($nextChapter),[],false) !!}" class="btn1 ml1 btn-min-w">
                                <div class="btn-top-label1">Next Chapter</div>
                                <div class="btn-sub-label1">Genesis 11</div>
                            </a>
                        @endif
                        @if($nextBook = $content['pagination']['bookNext'])
                            <a href="{!! url('reader/read?'.http_build_query($nextBook),[],false) !!}" class="btn1 ml1 btn-min-w">
                                <div class="btn-top-label1">Next Chapter</div>
                                <div class="btn-sub-label1">Exodus</div>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>


        @if($content['relatedItems']->count() && !Request::input('compare',false))

            <div class="col-md-3 related-records">
                <h3 class="text-center" style="margin: 30px auto 30px;">Related Records</h3>
                <div class="date-order-btn">
                    <a href="{!! url('reader/read?'.http_build_query(array_merge(Request::input(),['relations-order' => Request::get('relations-order','desc') == 'asc'?'desc':'asc'])),[],false) !!}">
                        <span class="fa fa-calendar"></span>
                        <span class="fa fa-sort-amount-{!! Request::get('relations-order','desc') !!}"></span>
                    </a>
                </div>

                @foreach($content['relatedItems'] as $item)
                    <div class="related-item">
                        <div class="item-header">
                            <i title="{!! ucfirst($item->type) !!} Entry"
                               class="pull-left fa fa-btn {!! ViewHelper::getRelatedItemIcon($item->type) !!}"></i>
                            <div class="pull-left">
                                <a href="#verse{!! $item->verse->id !!}" data-verseid="{!! $item->verse->id !!}" class="j-highlight-verse">{!! ViewHelper::getVerseNum($item->verse) !!}</a>
                            </div>
                            <a title="My Study Verse" href="{!! url('reader/my-study-verse?'.http_build_query([
                                'version' => $content['version_code'],
                                'book' => $item->verse->book_id,
                                'chapter' => $item->verse->chapter_num,
                                'verse' => $item->verse->verse_num,
                            ]),[],false) !!}">
                                {{--<i class="fa fa-pencil pull-right" aria-hidden="true"></i>--}}
                                <i class="fa fa-location-arrow fa-graduation-cap pull-right" aria-hidden="true"></i>
                            </a>
                            <div class="pull-right">{!! $item->created_at->format($item::DFORMAT) !!}</div>
                        </div>
                        <div class="item-body j-item-body" data-itemid="{!! $item->id !!}" , data-itemtype="{!! $item->type !!}">
                                @if($item->highlighted_text)
                                <div class="verse-block">
                                    Verse: <i>{!! str_limit(strip_tags($item->highlighted_text,'<p></p>'), $limit = 100, $end = '...') !!}</i>
                                </div>
                                @endif
                            {!! str_limit(strip_tags($item->text,'<p></p>'), $limit = 100, $end = '...') !!}
                        </div>
                    </div>
                @endforeach

            </div>
        @endif
    </div>
@stop
