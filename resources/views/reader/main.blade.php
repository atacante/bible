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
    <div class="j-verses-filters">
        {!! Form::open(['method' => 'get','url' => '/reader/'.(isset($filterAction)?$filterAction:'read')]) !!}

            <div class="j-chapter-content ">
                <div class="row" style="position: relative;">
                    <div class="col-lg-12">
                        <div class="c-title-and-icons">
                            <!-- Go to www.addthis.com/dashboard to customize your tools -->
                            <div class="addthis_sharing_toolbox c-sharing top-vertical1"></div>

                            <div class="text-center">
                                @if($prevChapter = $content['pagination']['chapterPrev'])
                                    <a class="genesis-arrow" title="Prev Chapter" href="{!! url('reader/read?'.http_build_query($prevChapter),[],false) !!}"><i class="bs-arrowleft cu-arrowleft"></i></a>
                                @endif


                                <div class="genesis-panel">
                                    <div class="sel-version">
                                        {!! Form::select('version', array_merge((Request::segment(2) == 'verse'?[]:['all' => 'All Versions']),$filters['versions']), Request::input('version','all'),['class' => 'j-select2 genesis-select']) !!}
                                    </div>
                                    <div class="sel-book">
                                    {!! Form::select('book', $filters['books'], Request::input('book'),['class' => 'j-select2 genesis-select']) !!}
                                    </div>
                                    <div class="sel-chapter">
                                    {!! Form::select('chapter',$filters['chapters'], Request::input('chapter'),['class' => 'j-select2 genesis-select']) !!}
                                    </div>

                                    @if(Request::segment(2) == 'verse')
                                        <div class="sel-chapter">
                                        {!! Form::select('verse',$filters['verses'], Request::input('verse'),['class' => 'j-select2 genesis-select']) !!}
                                        </div>
                                    @endif
                                    {!! Form::token() !!}
                                    {!! Form::submit('Go',['class' => 'genesis-btn']) !!}
                                </div>


                                <div class="popup-new j-popup-compare c-popup-compare" style="display: none">
                                    <div class="popup-arrow"></div>
                                    <h4 class="popup-title">
                                        COMPARE WITH...
                                    </h4>
                                    @if(isset($compare['versions']))
                                        <div class="sel-compare-versions mt17">
                                            {!! Form::select('compare[]', array_merge([],$compare['versions']), Request::input('compare'),['placeholder' => 'Start Typing Version Name (or Language)','multiple' => true, 'class' => 'j-compare-versions', 'style'=>'width:100%;']) !!}
                                        </div>
                                        {!! Form::submit('Compare',['class' => 'btn1 cu-btn1 mt17']) !!}
                                        {!! Html::link(url('reader/read?'.http_build_query($compare['resetParams']),[],false), 'Reset', ['class' => 'btn2 cu-btn2 mt17','style' => 'margin-left:10px;'], true) !!}
                                    @endif
                                </div>




                                    @if($nextChapter = $content['pagination']['chapterNext'])
                                        <a class="genesis-arrow" title="Next Chapter" href="{!! url('reader/read?'.http_build_query($nextChapter),[],false) !!}"><i class="bs-arrowright cu-arrowright"></i></a>
                                    @endif

                            </div>
                            <ul class="icon-panel top-vertical1">
                                <li>
                                    <a href="#" class="j-btn-compare">
                                        <i class="bs-compare cu-print"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="j-print-chapter">
                                        <i class="bs-print cu-print"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="j-btn-settings">
                                        <i class="bs-settings cu-print"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                    </div>
                </div>





                {{-- ------------------- CENTER CONTENT ---------------------- --}}
                <div class="{!! !$content['relatedItems']->count() || Request::input('compare',false)?'row':'my1-row' !!}">

                    {{-- ---------------- READER CONTENT ---------------- --}}
                    <div class="{!! !$content['relatedItems']->count() || Request::input('compare',false)?'col-md-12':'my1-col-md-8' !!}">
                        <div class="c-reader-content2"></div>
                        <div class="row" style="position: relative">
                                {!! (Request::input('compare',false)?count(Request::input('compare',false)) == 1?'<div class="comp-bord1"></div>':'<div class="comp-bord2"></div><div class="comp-bord3"></div>':"") !!}
                                <div class="j-reader-block j-bible-text {!! (Request::input('compare',false)?count(Request::input('compare',false)) == 1?'col-xs-6':'col-xs-4':"col-xs-12") !!}">
                                    <div class="inner-pad1">
                                        @if(Request::input('compare',false))
                                            <h4 class="version-title">{!! $content['version'] !!}</h4>
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
                                <div class="col-xs-{!! count($compareParam) == 1?'6':'4' !!} j-diff-block j-bible-text" data-version="{!! $version['version_code'] !!}">
                                    <div class="inner-pad1">
                                        <h4 class="version-title">
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
                                </div>
                                @endforeach
                                    {!! Html::link(url('reader/read?'.http_build_query($compare['resetParams']),[],false), '&#215;', ['class' => 'btn-reset'], true) !!}
                                @endif

                        </div>

                    </div>

                    {{-- ---------------- RELATED POSTS ---------------- --}}
                    @if($content['relatedItems']->count() && !Request::input('compare',false))

                    <div class="my1-col-md-4 related-records">
                        <div class="c-reader-content2"></div>
                            <div class="inner-pad1 bord1">
                                <h3 class="h3-related"><i class="bs-staroutlined cu-staroutlined"></i>Related Records <span class="ins-count">({{$content['relatedItems']->count()}})</span></h3>
                                <div class="date-order-btn">
                                    <a href="{!! url('reader/read?'.http_build_query(array_merge(Request::input(),['relations-order' => Request::get('relations-order','desc') == 'asc'?'desc':'asc'])),[],false) !!}">
                                        <span class="fa fa-calendar"></span>
                                        <span class="fa fa-sort-amount-{!! Request::get('relations-order','desc') !!}"></span>
                                    </a>
                                </div>
                            </div>


                            @foreach($content['relatedItems'] as $item)
                            <div class="inner-pad2 bord1">
                                <div class="related-item">
                                    <div class="item-header">
                                        <a class="a-study" title="My Study Verse" href="{!! url('reader/my-study-verse?'.http_build_query([
                                                'version' => $content['version_code'],
                                                'book' => $item->verse->book_id,
                                                'chapter' => $item->verse->chapter_num,
                                                'verse' => $item->verse->verse_num,
                                            ]),[],false) !!}">
                                            <i class="bs-study cu-study" aria-hidden="true"></i>
                                        </a>

                                        <a href="#verse{!! $item->verse->id !!}" data-verseid="{!! $item->verse->id !!}" class="j-highlight-verse title-verse">{!! ViewHelper::getVerseNum($item->verse) !!}</a>


                                        <div class="verse-date">{!! $item->created_at->format($item::DFORMAT2) !!}</div>
                                    </div>
                                    <div class="item-body j-item-body" data-itemid="{!! $item->id !!}" , data-itemtype="{!! $item->type !!}">
                                        @if($item->highlighted_text)
                                            <div class="verse-block">
                                                Verse: {!! str_limit(strip_tags($item->highlighted_text,'<p></p>'), $limit = 100, $end = '...') !!}
                                            </div>
                                        @endif
                                        <div class="verse-block-bott">
                                            <i title="{!! ucfirst($item->type) !!} Entry" class="verse-icon {!! ViewHelper::getRelatedItemIcon($item->type) !!}"></i>
                                            {!! str_limit(strip_tags($item->text,'<p></p>'), $limit = 100, $end = '...') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach



                    </div>
                    @endif

                </div>
            </div>




        {{-- ---------------- Pagination  ---------------- --}}
        <div class="row mt14 mb1">
            <div class="col-lg-12">
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






        {!! Form::close() !!}
    </div>
@stop
