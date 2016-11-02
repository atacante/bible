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
                            @if($content['showRelated'])
                                @role('user')
                                    <li class="btn-related-rec-resp">
                                        <a href="#" class="j-btn-related-rec">
                                            <i title="Related records" class="bs-staroutlined cu-print"></i>
                                        </a>
                                    </li>
                                @endrole
                            @endif
                            <li>
                                <a href="{!! url('reader/delete-bookmark',[App\User::BOOKMARK_CHAPTER,$content['version_code'],$content['verses'][0]->id]) !!}" class="j-bookmark {!! ViewHelper::checkBookmark(App\User::BOOKMARK_CHAPTER,$content['verses'][0])?'':'hidden' !!}">
                                    <i title="Remove from bookmarks" class="fa fa-bookmark cu-print"></i>
                                </a>
                                <a href="{!! url('reader/bookmark',[App\User::BOOKMARK_CHAPTER,$content['version_code'],$content['verses'][0]->id]) !!}" class="j-bookmark {!! !ViewHelper::checkBookmark(App\User::BOOKMARK_CHAPTER,$content['verses'][0])?'':'hidden' !!}">
                                    <i title="Add to bookmarks" class="fa fa-bookmark-o cu-print"></i>
                                </a>
                            </li>

                            <li>
                                <a href="#" class="j-btn-compare">
                                    <i title="Compare versions" class="bs-compare cu-print"></i>
                                </a>
                            </li>
                            @if(Agent::isModile())
                            <li>
                                <a href="#" class="j-print-chapter">
                                    <i title="Print" class="bs-print cu-print"></i>
                                </a>
                            </li>
                            @endif
                            <li>
                                <a href="#" class="j-btn-settings">
                                    <i title="Settings" class="bs-settings cu-print"></i>
                                    @if(ViewHelper::checkNotifTooltip('got_related_records_tooltip') || ViewHelper::checkNotifTooltip('got_chapter_diff_tooltip'))
                                    <i title="Settings" class="bs-starsolid cu-starsolid-settings"></i>
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
            {!! Form::close() !!}

            <!-- Mobile Related Records -->

            <div class="my1-col-md-4 related-records j-mobile-rel-rec" {{($content['showRelated'])?'':'style="display: none"'}}>
                <div class="popup-arrow"></div>
                @include('reader.related')
            </div>

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
                <div class="{!! !$content['showRelated'] || !$content['relatedItems']->count() || Request::input('compare',false)?'col-md-12':'my1-col-md-8' !!} {!! Request::input('compare', false)?'compare-mode':'' !!}">
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
                                        <span class="verse-text j-verse-text {!! (Auth::check() && in_array(Auth::user()->id,$verse->bookmarks->modelKeys())?'j-bookmarked':'') !!}" data-version="{!! $content['version_code'] !!}"
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
                <div class="my1-col-md-4 related-records j-desktop-rel-rec">
                    @include('reader.related')
                </div>

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
                        progress and share with new friends?<br>
                        <a href="{{url('/auth/register')}}" class="btn2-kit banner-btn">Sign Up Now</a>
                    </h2>
                </div>
            </div>
        @endif
    </div>
@stop
