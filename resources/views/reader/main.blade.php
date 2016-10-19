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
        {!! Form::open(['method' => 'get','url' => '/reader/'.(isset($filterAction)?$filterAction:'read'), 'id'=>'j-sel-book-form']) !!}
        {!! Form::hidden('diff','on') !!}



        <div class="permonent-pop j-choose-version-pop" style="display: none;">
            <div class="pp-title">
                CHOOSE Version
                <a href="#" class="btn-reset cu-btr1 j-close-choose-version">&#215;</a>
            </div>
            <ul class="pp-c-items j-version-list">
                @foreach ($filters['versions'] as $val=>$version)
                    <li><a class="{{ $val==$content["version_code"]?"active":"" }}" data-val="{{$val}}" href="#">{{ $filters['versions'][$val] }}</a></li>
                @endforeach
            </ul>
        </div>



        {{-- --}}
        <div class="j-chapter-content ">
            <div class="row j-nav-sel resp-offset" style="position: relative;">
                <div class="col-lg-12">
                    <div class="text-center">
                        <div class="genesis-panel">
                            <h4 class="h4-sel-version j-sel-version-label"><span class="j-sel-version-text">{!! $content['version'] !!}</span> <span class="c-arrow-sel"><b></b></span></h4>

                            <div class="sel-version" style="display: none">
                                {!! Form::select('version', array_merge((Request::segment(2) == 'verse'?[]:['all' => 'All Versions']),$filters['versions']), Request::input('version','all'),['class' => 'genesis-select j-select-version']) !!}
                            </div>
                        </div>
                    </div>

                    <div class="permonent-pop j-choose-book-pop" style="display: none;">
                        <div class="pp-title">
                            CHOOSE BOOK
                            <a href="#" class="btn-reset cu-btr1 j-close-choose-book">&#215;</a>
                        </div>
                        {{-- desctop version --}}
                        <table class="t-choose-book j-book-list j-choose-desctop" style="width:100%; display: none;">
                            <?php
                            $n = 0;
                            $book_mas[$n] = "";
                            foreach ($filters['books'] as $val=>$book){
                                $n++;
                                $book_mas[$n]['val'] = $val;
                                $book_mas[$n]['text'] = $filters['books'][$val];
                            }
                            $count_columns = 4;
                            $count_books = count($filters['books']);
                            $count_books_on_column =  ceil ((((int)$count_books) / $count_columns));
                            $b = 0;
                            for ($i=1; $i<=$count_columns; $i++) {
                                for ($j=1; $j<=$count_books_on_column; $j++) {
                                    $b++;
                                    if (isset($book_mas[$b]["text"])) {
                                        $mas[$i][$j] = $book_mas[$b]["val"];
                                    } else {
                                        $mas[$i][$j] = "";
                                    }
                                }
                            }
                            for($j=1; $j<=$count_books_on_column; $j++) {
                                echo '<tr>';
                                for ($i=1; $i<=$count_columns; $i++){
                                    if($mas[$i][$j]>0){
                                        $val1 = $mas[$i][$j];
                                        $active = Request::input('book',1)==$mas[$i][$j]?'active':'';
                                        echo '<td><a class="'.$active.'" data-val="'.$mas[$i][$j].'" href="#">'.$filters['books'][$val1].'</a></td>';
                                    } else {
                                        echo '<td></td>';
                                    }
                                }
                                echo '</tr>';
                            }
                            ?>
                        </table>

                        {{-- mobile version --}}
                        <div class="j-choose-mobile" style="display: none;">
                            <table class="t-choose-book j-book-list " style="width:100%;">
                                <?php
                                $n = 0;
                                $book_mas[$n] = "";
                                foreach ($filters['books'] as $val=>$book){
                                    $n++;
                                    $book_mas[$n]['val'] = $val;
                                    $book_mas[$n]['text'] = $filters['books'][$val];
                                }
                                $count_columns = 1;
                                $count_books = count($filters['books']);
                                $count_books_on_column =  ceil ((((int)$count_books) / $count_columns));
                                $b = 0;
                                for ($i=1; $i<=$count_columns; $i++) {
                                    for ($j=1; $j<=$count_books_on_column; $j++) {
                                        $b++;
                                        if (isset($book_mas[$b]["text"])) {
                                            $mas[$i][$j] = $book_mas[$b]["val"];
                                        } else {
                                            $mas[$i][$j] = "";
                                        }
                                    }
                                }
                                for($j=1; $j<=$count_books_on_column; $j++) {
                                    echo '<tr>';
                                    for ($i=1; $i<=$count_columns; $i++){
                                        if($mas[$i][$j]>0){
                                            $val1 = $mas[$i][$j];
                                            $active = Request::input('book',1)==$mas[$i][$j]?'active':'';
                                            echo '<td><a class="'.$active.'" data-val="'.$mas[$i][$j].'" href="#">'.$filters['books'][$val1].'</a></td>';
                                        } else {
                                            echo '<td></td>';
                                        }
                                    }
                                    echo '</tr>';
                                }
                                ?>
                            </table>
                        </div>
                    </div>



                    <div class="c-title-and-icons j-nav-sel2">
                        <!-- Go to www.addthis.com/dashboard to customize your tools -->
                        <div class="addthis_inline_share_toolbox c-sharing top-vertical1"></div>

                        <div class="text-center">
                            @if($prevChapter = $content['pagination']['chapterPrev'])
                                <a class="genesis-arrow ga-left" title="Prev Chapter" href="{!! url('reader/read?'.http_build_query($prevChapter),[]) !!}"><i class="bs-arrowleft cu-arrowleft"></i></a>
                            @else
                                <span class="genesis-arrow ga-left"><i class="bs-arrowleft cu-arrowleft2"></i></span>
                            @endif

                            <div class="genesis-panel">
                                <div class="sel-book">
                                    <h4 class="h4-sel-book j-sel-book-label"><span class="j-sel-book-text">{!! $filters['books'][Request::input('book',1)] !!}</span> <span class="c-arrow-sel"><b></b></span></h4>
                                    {!! Form::select('book', $filters['books'], Request::input('book'),['class' => 'genesis-select j-select-book', 'style' => 'display: none']) !!}
                                </div>
                            </div>

                            @if($nextChapter = $content['pagination']['chapterNext'])
                                <a class="genesis-arrow ga-right" title="Next Chapter" href="{!! url('reader/read?'.http_build_query($nextChapter),[]) !!}"><i class="bs-arrowright cu-arrowright"></i></a>
                            @else
                                <span class="genesis-arrow ga-right"><i class="bs-arrowright cu-arrowright2"></i></span>
                            @endif
                        </div>

                        {{-- Right icons panel --}}
                        <ul class="icon-panel top-vertical1">
                            {{--@role('user')--}}
                            <li>
                                <a href="{!! url('reader/delete-bookmark',[App\User::BOOKMARK_CHAPTER,$content['version_code'],$content['verses'][0]->id]) !!}" class="j-bookmark {!! ViewHelper::checkBookmark(App\User::BOOKMARK_CHAPTER,$content['verses'][0])?'':'hidden' !!}">
                                    <i title="Remove from bookmarks" class="fa fa-bookmark cu-print" style="font-size: 2rem;"></i>
                                </a>
                                <a href="{!! url('reader/bookmark',[App\User::BOOKMARK_CHAPTER,$content['version_code'],$content['verses'][0]->id]) !!}" class="j-bookmark {!! !ViewHelper::checkBookmark(App\User::BOOKMARK_CHAPTER,$content['verses'][0])?'':'hidden' !!}">
                                    <i title="Add to bookmarks" class="fa fa-bookmark-o cu-print"></i>
                                </a>
                            </li>
                            {{--@endrole--}}
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
                                    @if(ViewHelper::checkNotifTooltip('got_related_records_tooltip') || ViewHelper::checkNotifTooltip('got_chapter_diff_tooltip'))
                                    <i class="bs-starsolid cu-starsolid-settings"></i>
                                    @endif
                                </a>
                            </li>
                        </ul>
                    </div>

                    {{-- CHAPTER SELECT --}}
                    <div class="text-center j-nav-sel2 j-nav-sel3" style="margin-bottom: 50px;">
                        <div class="genesis-panel">
                            <div class="sel-chapter">
                                <h4 class="h4-sel-book j-sel-chapter-label"><span class="j-sel-chapter-text">{!! $filters['chapters'][Request::input('chapter',1)] !!}</span> <span class="c-arrow-sel"><b></b></span></h4>
                                {!! Form::select('chapter',$filters['chapters'], Request::input('chapter'),['class' => 'genesis-select j-select-chapter', 'style' => 'display:none;']) !!}
                            </div>

                            @if(Request::segment(2) == 'verse')
                                <div class="sel-chapter">
                                    {!! Form::select('verse',$filters['verses'], Request::input('verse'),['class' => 'j-select2 genesis-select']) !!}
                                </div>
                            @endif
                            {!! Form::token() !!}
                           {{-- {!! Form::submit('Go',['class' => 'genesis-btn j-genesis-btn']) !!}--}}
                        </div>
                    </div>
                    {{-- CHAPTER POPUP --}}
                    <div class="permonent-pop j-choose-chapter-pop" style="display: none;">
                        <div class="pp-title">
                            CHOOSE CHAPTER
                            <a href="#" class="btn-reset cu-btr1 j-close-choose-chapter">&#215;</a>
                        </div>
                        <ul class="c-chapter-list j-chapter-list">
                            @foreach ($filters['chapters'] as $val=>$chapter)
                                <li><a class="{{ $val==Request::input('chapter',1)?"active":"" }}" data-val="{{$val}}" href="#">{{ $filters['chapters'][$val] }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>


            {{-- COMPARE POPUP --}}
            <div class="popup-new j-popup-compare c-popup-compare" style="display: none">
                <div class="popup-arrow"></div>
                <h4 class="popup-title">
                    COMPARE WITH...
                </h4>
                @if(isset($compare['versions']))
                    {!! Form::select('compare[]', array_merge([],$compare['versions']), Request::input('compare'),['multiple' => true, 'class' => 'hidden','id' => 'compare-versions-select']) !!}
                    <ul class="compare-list j-compare-list">
                        @foreach($compare['versions'] as $key => $version)
                            @if(in_array($key,Request::input('compare',[])))
                                <li class="j-versions-to-compare-list" data-versioncode="{{ $key }}">
                                    {{ $version }}
                                    <a href="#" class="btn-reset j-remove-compare" style="position: relative; top: auto; right: auto; display: inline-block; font-size: 17px; height: 20px; width: 20px; line-height: 17px;">×</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    <input type="text" class="live-search-box j-live-search-box" placeholder="Start Typing Version Name (or Language)" />
                    <ul class="live-search-list j-live-search-list">
                        @foreach($compare['versions'] as $key => $version)
                            @if(!in_array($key,Request::input('compare',[])))
                                <li class="j-versions-to-compare-list" data-versioncode="{{ $key }}">
                                    {{ $version }}
                                    <a href="#" class="btn-reset j-remove-compare hidden" style="position: relative; top: auto; right: auto; display: inline-block; font-size: 17px; height: 20px; width: 20px; line-height: 17px;">×</a>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    {!! Form::submit('Compare',['class' => 'btn1 cu-btn1 mt17']) !!}
                    {!! Html::link(url('reader/read?'.http_build_query($compare['resetParams']),[]), 'Reset', ['class' => 'btn2 cu-btn2 mt17','style' => 'margin-left:10px;'], true) !!}
                @endif
            </div>

            @include('reader.settings_popup')


            @if((Request::input('compare', false) || Request::segment(2) == 'verse') && (!Request::input('diff',false) || Request::input('diff',false) == 'on'))
            <div class="row">
                <div class="col-xs-12 text-right legend-block">
                    <div class="legend-item">
                        <div class="legend-icon legend-del-color"></div>
                        <div class="legend-text">Removed text</div>
                    </div>
                    <div class="legend-item">
                        <div class="legend-icon legend-ins-color"></div>
                        <div class="legend-text">Added text</div>
                    </div>
                </div>
            </div>
            @endif
            {{-- ------------------- CENTER CONTENT ---------------------- --}}
            <div class="{!! !$content['showRelated'] || !$content['relatedItems']->count() || Request::input('compare',false)?'row':'my1-row' !!}">

                {{-- ---------------- READER CONTENT ---------------- --}}
                <div class="{!! !$content['showRelated'] || !$content['relatedItems']->count() || Request::input('compare',false)?'col-md-12':'my1-col-md-8' !!}">
                    <div class="c-reader-content2"></div>
                    <div class="row reader-text" style="position: relative">
                            {!! (Request::input('compare',false)?count(Request::input('compare',false)) == 1?'<div class="comp-bord1"></div>':'<div class="comp-bord2"></div><div class="comp-bord3"></div>':"") !!}
                            <div class="j-reader-block j-bible-text {!! (Request::input('compare',false)?count(Request::input('compare',false)) == 1?'col-xs-6':'col-xs-4':"col-xs-12") !!}">
                                <div class="j-dynamic-arrows" style="position: fixed; max-width: 1360px; height: 45px;  width: 1260px; display: none;">
                                    @if($prevChapter = $content['pagination']['chapterPrev'])
                                        <a class="genesis-arrow" title="Prev Chapter" href="{!! url('reader/read?'.http_build_query($prevChapter),[]) !!}" style="position: absolute; left:-40px;"><i class="bs-arrowleft cu-arrowleft"></i></a>
                                    @endif
                                    @if($nextChapter = $content['pagination']['chapterNext'])
                                        <a class="genesis-arrow" title="Next Chapter" href="{!! url('reader/read?'.http_build_query($nextChapter),[]) !!}" style="position: absolute; right:-40px;"><i class="bs-arrowright cu-arrowright"></i></a>
                                    @endif
                                </div>
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
                                                </b>&nbsp;{!! ViewHelper::prepareVerseText($verse,true,$content['readerMode']) !!}
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
                                        {!! Html::link(url('reader/read?'.http_build_query(array_merge(['compare' => array_diff($compareParam,[$version['version_code']])],$compare['resetParams'])),[]), '&#215;', ['class' => 'btn-reset btn-reset-compare j-btn-reset-compare','style' => ''], true) !!}
                                    </div>
                                @endforeach
                            @endif

                    </div>

                </div>

                {{-- ---------------- RELATED POSTS ---------------- --}}
                @if($content['showRelated'] && $content['relatedItems']->count() && !Request::input('compare',false))

                <div class="my1-col-md-4 related-records">
                    <div class="c-reader-content2"></div>
                        <div class="inner-pad1 bord1">
                            <h3 class="h3-related"><i class="bs-staroutlined cu-staroutlined"></i>Related Records <span class="ins-count">({{$content['relatedItems']->count()}})</span></h3>
                            <div class="date-order-btn">
                                <a href="{!! url('reader/read?'.http_build_query(array_merge(Request::input(),['relations-order' => Request::get('relations-order','desc') == 'asc'?'desc':'asc'])),[]) !!}">
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
                                            Verse: {!! str_limit(strip_tags($item->highlighted_text,'<p></p>'), $limit = 99999, $end = '...') !!}
                                        </div>
                                    @endif
                                    <div class="verse-block-bott">
                                        <i title="{!! ucfirst($item->type) !!} Entry" class="verse-icon {!! ViewHelper::getRelatedItemIcon($item->type) !!}"></i>
                                        {!! str_limit(strip_tags($item->text,'<p></p>'), $limit = 100, $end = '...') !!}
                                    </div>
                                    @if(Auth::user() && Auth::user()->isPremium() && $item->type == 'prayer')
                                            <div class="addthis_inline_share_toolbox j-custom-sharing" data-url="{{url('/community')}}" data-title="{!! strip_tags($item->text) !!}"></div>
                                    @endif
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
            <div class="col-xs-12 j-reader-pagination">

                <div class="reader-res-bott-panel-l pull-left">
                    @if($prevChapter = $content['pagination']['chapterPrev'])
                        <a href="{!! url('reader/read?'.http_build_query($prevChapter),[]) !!}" class="btn2 mr5 btn-min-w">
                            <div class="btn-top-label1">Prev Chapter</div>
                            <div class="btn-sub-label1">{{ $filters['books'][Request::input('book',1)].' '.$filters['chapters'][$prevChapter['chapter']] }}</div>
                        </a>
                    @else
                        <a href="#" class="btn2 mr5 btn-min-w" style="visibility: hidden">
                            <div class="btn-top-label1">Prev Chapter</div>
                            <div class="btn-sub-label1">none</div>
                        </a>
                    @endif
                    @if($prevBook = $content['pagination']['bookPrev'])
                        <a href="{!! url('reader/read?'.http_build_query($prevBook),[]) !!}" class="btn2 mr5 btn-min-w pull-left btn-book-resp-l">
                            <div class="btn-top-label1">Prev Book</div>
                            <div class="btn-sub-label1">{{ $filters['books'][$prevBook['book']] }}</div>
                        </a>
                    @endif
                </div>

                <div class="reader-res-bott-panel-r pull-right">
                    @if($nextChapter = $content['pagination']['chapterNext'])
                        <a href="{!! url('reader/read?'.http_build_query($nextChapter),[]) !!}" class="btn1 ml1 btn-min-w">
                            <div class="btn-top-label1">Next Chapter</div>
                            <div class="btn-sub-label1">{{ $filters['books'][Request::input('book',1)].' '.$filters['chapters'][$nextChapter['chapter']] }}</div>
                        </a>
                    @else
                        <a href="#" class="btn1 ml1 btn-min-w" style="visibility: hidden">
                            <div class="btn-top-label1">Next Chapter</div>
                            <div class="btn-sub-label1">none</div>
                        </a>
                    @endif
                    @if($nextBook = $content['pagination']['bookNext'])
                        <a href="{!! url('reader/read?'.http_build_query($nextBook),[]) !!}" class="btn1 ml1 btn-min-w btn-book-resp-r">
                            <div class="btn-top-label1">Next Book</div>
                            <div class="btn-sub-label1">{{ $filters['books'][$nextBook['book']] }}</div>
                        </a>
                    @endif
                </div>

            </div>
        </div>

        @if(!Auth::check())
            <div class="row mb1-r mb1 mt12">
                <div class="col-xs-12 reader-banner">
                    <h2 class="h2-2 color1 text-center text-uppercase">
                        want to keep your study<br>
                        progress and share with a new friends?<br>
                        <a href="{{url('/auth/register')}}" class="btn2-kit banner-btn">Sign Up Now</a>
                    </h2>
                </div>
            </div>
        @endif
        {!! Form::close() !!}
    </div>
@stop
