@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    {{--<div class="row col-md-12">
        @include('reader.filters')
    </div>--}}
    {!! Form::hidden('verse_details',true) !!}
    {!! Form::hidden('bible_version',$content['main_verse']['version_code']) !!}
    {!! Form::hidden('verse_id',$content['main_verse']['verse']->id) !!}
    {!! Form::open(['method' => 'get','url' => '/reader/'.(isset($filterAction)?$filterAction:'verse'), 'id'=>'j-sel-book-form']) !!}
    <div class="row">
        <div class="permonent-pop j-choose-version-pop" style="display: none;">
            <div class="pp-title">
                CHOOSE Version
                <a href="#" class="btn-reset cu-btr1 j-close-choose-version">&#215;</a>
            </div>
            <ul class="pp-c-items j-version-list">
                {{--<li><a  data-val="all" href="#">All Versions</a></li>--}}
                @foreach ($filters['versions'] as $val=>$version)
                    <li><a class="{{ $val==$content['main_verse']['version_code']?"active":"" }}" data-val="{{$val}}" href="#">{{ $filters['versions'][$val] }}</a></li>
                @endforeach
            </ul>
        </div>
        <div class="col-xs-12">
            <div class="row j-nav-sel" style="position: relative;">
                <div class="col-lg-12">
                    <div class="text-center">
                        <div class="genesis-panel">
                            <h4 class="h4-sel-version j-sel-version-label"><span class="j-sel-version-text">{!! $content['main_verse']['version_name'] !!}</span> <span class="c-arrow-sel"><b></b></span></h4>

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
                        <table class="t-choose-book j-book-list" style="width:100%;">
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
                    </div>
                    <div class="c-title-and-icons j-nav-sel2">
                        <a href="{!! url('reader/read?'.http_build_query(
                            [
                                'version' => $content['main_verse']['version_code'],
                                'book' => $content['main_verse']['verse']->book_id,
                                'chapter' => $content['main_verse']['verse']->chapter_num
                            ])."#verse".$content['main_verse']['verse']->id,[],false) !!}"
                            class="btn1-kit cu1-btn">
                            <i class="bs-arrowback cu-arrowback"></i>
                            Back to Reader
                        </a>

                        <div class="text-center">
                            @if($versePrev = $content['pagination']['versePrev'])
                                <a class="genesis-arrow" title="Prev Verse" href="{!! url('reader/verse?'.http_build_query($versePrev),[]) !!}"><i class="bs-arrowleft cu-arrowleft"></i></a>
                            @else
                                <span class="genesis-arrow"><i class="bs-arrowleft cu-arrowleft2"></i></span>
                            @endif

                            <div class="genesis-panel">
                                <div class="sel-book">
                                    <h4 class="h4-sel-book j-sel-book-label"><span class="j-sel-book-text">{!! $filters['books'][Request::input('book',1)] !!}</span> <span class="c-arrow-sel"><b></b></span></h4>
                                    {!! Form::select('book', $filters['books'], Request::input('book'),['class' => 'genesis-select j-select-book', 'style' => 'display: none']) !!}
                                </div>
                            </div>

                            @if($verseNext = $content['pagination']['verseNext'])
                                <a class="genesis-arrow" title="Next Verse" href="{!! url('reader/verse?'.http_build_query($verseNext),[]) !!}"><i class="bs-arrowright cu-arrowright"></i></a>
                            @else
                                <span class="genesis-arrow"><i class="bs-arrowright cu-arrowright2"></i></span>
                            @endif
                        </div>

                        {{-- Right icons panel --}}
                        <div class="btns-panel">
                            <a href="{{ url('/notes/create') }}" class="btn1-kit j-create-note"><i class="bs-add"></i> Note</a>
                            <a href="{{ url('/journal/create') }}" class="btn1-kit j-create-journal"><i class="bs-add"></i> Journal</a>
                            <a href="{{ url('/prayers/create') }}" class="btn1-kit j-create-prayer"><i class="bs-add"></i> Prayer</a>
                        </div>
                    </div>

                    {{-- CHAPTER SELECT --}}
                    <div class="text-center j-nav-sel2 j-nav-sel3">
                        <div class="genesis-panel">
                            <div class="sel-chapter">
                                <h4 class="h4-sel-book j-sel-chapter-label"><span class="j-sel-chapter-text">{!! $filters['chapters'][Request::input('chapter',1)] !!}</span> <span class="c-arrow-sel"><b></b></span></h4>
                                {!! Form::select('chapter',$filters['chapters'], Request::input('chapter'),['class' => 'genesis-select j-select-chapter', 'style' => 'display:none;']) !!}
                                <span style="font-size: 30px;">:</span>
                                <h4 class="h4-sel-book j-sel-verse-label"><span class="j-sel-verse-text">{!! $filters['verses'][Request::input('verse',1)] !!}</span> <span class="c-arrow-sel"><b></b></span></h4>
                                {!! Form::select('verse',$filters['verses'], Request::input('verse'),['class' => 'genesis-select j-select-verse', 'style' => 'display:none;']) !!}
                            </div>
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
                            @foreach ($filters['chapters'] as $key=>$chapter)
                                <li><a class="{{ $key==Request::input('chapter',1)?"active":"" }}" data-val="{{$key}}" href="#">{{ $filters['chapters'][$key] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    {{-- CHAPTER POPUP --}}
                    <div class="permonent-pop j-choose-verse-pop" style="display: none;">
                        <div class="pp-title">
                            CHOOSE VERSE
                            <a href="#" class="btn-reset cu-btr1 j-close-choose-verse">&#215;</a>
                        </div>
                        <ul class="c-chapter-list j-verse-list">
                            @foreach ($filters['verses'] as $key=>$verse)
                                <li><a class="{{ $key==Request::input('verse',1)?"active":"" }}" data-val="{{$key}}" href="#">{{ $filters['verses'][$key] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}

    <div class="row">
        <div class="col-xs-12">
            <div class="c-verse-text-top">
                <div class="c-queries">“</div>
                <h4 class="j-bible-text">
                    <span class="verse-text color4" data-version="{!! $content['main_verse']['version_code'] !!}" data-verseid="{!! $content['main_verse']['verse']->id !!}">{!! $content['main_verse']['verse']->verse_text /*ViewHelper::prepareVerseText($content['main_verse']['verse'],true)*/ !!}</span>
                </h4>
                <div class="bookmark-btn">
                    <a href="{!! url('reader/delete-bookmark',[App\User::BOOKMARK_VERSE,$content['main_verse']['version_code'],$content['main_verse']['verse']->id]) !!}" class="j-bookmark {!! ViewHelper::checkBookmark(App\User::BOOKMARK_VERSE,$content['main_verse']['verse'])?'':'hidden' !!}">
                        <i title="Remove from bookmarks" class="fa fa-bookmark cu-print" style="font-size: 2rem;"></i>
                    </a>
                    <a href="{!! url('reader/bookmark',[App\User::BOOKMARK_VERSE,$content['main_verse']['version_code'],$content['main_verse']['verse']->id]) !!}" class="j-bookmark {!! !ViewHelper::checkBookmark(App\User::BOOKMARK_VERSE,$content['main_verse']['verse'])?'':'hidden' !!}">
                        <i title="Add to bookmarks" class="fa fa-bookmark-o cu-print" style="font-size: 2rem;"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="j-dynamic-arrows" style="position: fixed; max-width: 1360px; height: 45px;  width: 1260px; display: none;">
        @if($versePrev = $content['pagination']['versePrev'])
            <a class="genesis-arrow" title="Prev Verse" href="{!! url('reader/verse?'.http_build_query($versePrev),[]) !!}" style="position: absolute; left:-40px;"><i class="bs-arrowleft cu-arrowleft"></i></a>
        @endif
        @if($verseNext = $content['pagination']['verseNext'])
            <a class="genesis-arrow" title="Next Verse" href="{!! url('reader/verse?'.http_build_query($verseNext),[]) !!}" style="position: absolute; right:0px;"><i class="bs-arrowright cu-arrowright"></i></a>
        @endif
    </div>
    @if(count($content['lexicon']))
        <div class="row">
            <div class="col-xs-12">
                <h2 class="h2-new mt18 mb3">
                    <i class="bs-lexicon cu-lexicon"></i>
                    Lexicon
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table-lexicon">
                    @foreach($content['lexicon'] as $lexiconinfo)
                        @if(!empty($lexiconinfo->verse_part))
                            <tr>
                                <td class="orange-bord t-label1" @if($lexiconinfo->symbolism) rowspan="2" @endif >{!! $lexiconinfo->verse_part !!}</td>
                                <td @if(!$lexiconinfo->symbolism) class="orange-bord" @endif><span class="t-label2">Definition:</span> {!! $lexiconinfo->definition !!}</td>
                                <td @if(!$lexiconinfo->symbolism) class="orange-bord" @endif><span class="t-label2">Strong's:</span> {!! link_to('/reader/strongs/'.preg_replace("/[^0-9]/","",$lexiconinfo->strong_num).$lexiconinfo->strong_num_suffix."/".ViewHelper::detectStrongsDictionary($lexiconinfo),$lexiconinfo->strong_num) !!}</td>
                                <td @if(!$lexiconinfo->symbolism) class="orange-bord" @endif><span class="t-label2">Transliteration:</span> {!! $lexiconinfo->transliteration !!}</td>
                            </tr>
                            @if($lexiconinfo->symbolism)
                            <tr>
                                <td class="orange-bord" colspan="3">
                                    <span class="t-label2">Analysis:</span>
                                     <br>
                                    {!! $lexiconinfo->symbolism !!}
                                </td>
                            </tr>
                            @endif
                        @endif
                    @endforeach
                </table>
            </div>
        </div>
    @endif


    {{-- ------- Locations -------- --}}
    @if($content['main_verse']['verse']->locations->count())

        <div class="row">
            <div class="col-xs-12">
                <h2 class="h2-new mt18 mb3">
                    <i class="bs-places cu-places"></i>
                    Location{!! ($content['main_verse']['verse']->locations->count() > 0?'s':'') !!}
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                @foreach($content['main_verse']['verse']->locations as $location)
                    <div class="c-white-item mb4 cu-ci1">
                        <div class="cu-ci1-inner">
                            @if($location->images->count())
                                <div id="location-{!! $location->id !!}" class="carousel slide" data-ride="carousel" data-interval="{!! rand(5000,7000) !!}">
                                    <!-- Indicators -->
                                    @if($location->images->count() > 1)
                                        <ol class="carousel-indicators">
                                            @foreach($location->images as $key => $image)
                                                <li data-target="#location-{!! $location->id !!}"
                                                    data-slide-to="{!! $key !!}"
                                                    class="{!! ($key == 0?'active':'') !!}">
                                                </li>
                                            @endforeach
                                        </ol>
                                        @endif

                                        <!-- Wrapper for slides -->
                                        <div class="carousel-inner" role="listbox">
                                            @foreach($location->images as $key => $image)
                                                <div class="item {!! ($key == 0?'active':'') !!} j-with-images">
                                                    {{--<img src="{!! Config::get('app.locationImages').'thumbs/'.$image->image !!}"  class="img-thumbnail verse-image" alt="" style="cursor: pointer;">--}}
                                                    <div class="people-image verse-image" data-image="{!! $image->image!=''?Config::get('app.locationImages').'thumbs/'.$image->image:'' !!}" style="background: url('{!! $image->image!=''?Config::get('app.locationImages').'thumbs/'.$image->image:'' !!}') center no-repeat;"></div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Controls -->
                                        @if($location->images->count() > 1)
                                            <a class="left carousel-control" href="#location-{!! $location->id !!}"
                                               role="button" data-slide="prev">
                                                <span class="bs-arrowleft" aria-hidden="true"></span>
                                                <span class="sr-only">Previous</span>
                                            </a>
                                            <a class="right carousel-control" href="#location-{!! $location->id !!}"
                                               role="button" data-slide="next">
                                                <span class="bs-arrowright" aria-hidden="true"></span>
                                                <span class="sr-only">Next</span>
                                            </a>
                                        @endif
                                </div>
                            @endif
                        </div>
                        <div class="inner-pad3">
                            <h4 class="title-people">{!! $location->location_name !!}</h4>
                            <p class="p-medium2">
                                {!! str_limit(strip_tags($location->location_description,''), $limit = 360, $end = '... '.Html::link(url('/locations/view/'.$location->id,[]), 'View Details', ['class' => 'a-kit'], true)) !!}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif


    {{-- -------People -------- --}}
    @if($content['main_verse']['verse']->peoples->count())
        <div class="row">
            <div class="col-xs-12">
                <h2 class="h2-new mt18 mb3">
                    <i class="bs-people cu-people"></i>
                    People
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
            @foreach($content['main_verse']['verse']->peoples as $people)
                <div class="c-white-item mb4 cu-ci1">
                    <div class="cu-ci1-inner">
                        @if($people->images->count())
                            <div id="people-{!! $people->id !!}" class="carousel slide" data-ride="carousel" data-interval="{!! rand(5000,7000) !!}">
                                <!-- Indicators -->
                                @if($people->images->count() > 1)
                                    <ol class="carousel-indicators">
                                        @foreach($people->images as $key => $image)
                                            <li data-target="#people-{!! $people->id !!}"
                                                data-slide-to="{!! $key !!}"
                                                class="{!! ($key == 0?'active':'') !!}">
                                            </li>
                                        @endforeach
                                    </ol>
                                    @endif

                                            <!-- Wrapper for slides -->
                                    <div class="carousel-inner" role="listbox">
                                        @foreach($people->images as $key => $image)
                                            <div class="item {!! ($key == 0?'active':'') !!} j-with-images">
                                                <div class="img-thumbnail people-image verse-image" data-image="{!! $image->image!=''?Config::get('app.peopleImages').'thumbs/'.$image->image:'' !!}" style="background: url('{!! $image->image!=''?Config::get('app.peopleImages').'thumbs/'.$image->image:'' !!}') center no-repeat;"></div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Controls -->
                                    @if($people->images->count() > 1)
                                        <a class="left carousel-control" href="#people-{!! $people->id !!}"
                                           role="button" data-slide="prev">
                                            <span class="bs-arrowleft" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="right carousel-control" href="#people-{!! $people->id !!}"
                                           role="button" data-slide="next">
                                            <span class="bs-arrowright" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    @endif
                            </div>
                        @endif
                    </div>
                    <div class="inner-pad3">
                        <h4 class="title-people">{!! $people->people_name !!}</h4>
                        <p class="p-medium2">
                            {!! str_limit(strip_tags($people->people_description,''), $limit = 460, $end = '... '.Html::link(url('/peoples/view/'.$people->id,[]), 'View Details', ['class' => 'a-kit'], true)) !!}
                        </p>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    @endif

    {{-- ------------- Parallel Verses ------------- --}}
    <div id="parallel-verses" class="row parallel-verses j-parallel-verses {!! Request::input('diff', false)?'j-compare-verses':'' !!}">
        <div class="col-xs-12">
            <div class="c-title-and-icons3 mt18 mb3">
                <h2 class="h2-new">
                    <i class="bs-parallel cu-lexicon"></i>
                    Parallel Verses {{--{!! link_to('reader/verse?'.http_build_query(array_merge(Request::input(),['diff' => Request::input('diff',false)?0:1])), (Request::input('diff',false)?'hide':'show').' diff',['class' => 'btn btn-'.(Request::input('diff',false)?'danger':'success'), 'style' =>'padding: 0 5px;']) !!}--}}
                </h2>
                <a class="btn-settings j-btn-settings" href="#">
                    <i class="bs-settings cu-print"></i>
                    @if(ViewHelper::checkNotifTooltip('got_verse_diff_tooltip'))
                    <i class="bs-starsolid cu-starsolid-settings"></i>
                    @endif
                </a>
            </div>
        </div>
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
    <div class="row j-bible-text">
        <div class="col-xs-12">
            <div class="c-white-item inner-pad3 mb4">
                {{ Html::link(url('reader/read?'.http_build_query(array_merge(Request::input(),['version' => Request::input('version')])),[]), $content['main_verse']['version_name'], ['class' => 'paralel-link'], true)}}
                <span class="verse-text j-verse-text p-medium2" data-version="{!! $content['main_verse']['version_code'] !!}"  data-verseid="{!! $content['main_verse']['verse']->id !!}">
                    {!! ViewHelper::prepareVerseText($content['main_verse']['verse'],true) !!}
                </span>
            </div>
        </div>
    </div>

    <div class="row j-bible-text">
        <div class="col-xs-12">
            @foreach($content['verse'] as $code => $version)
                @if($version['verse'])
                    <div class="c-white-item inner-pad3 mb4">
                        {{ Html::link(url('reader/read?'.http_build_query(array_merge(Request::input(),['version' => $code])),[]), $version['version_name'], ['class' => 'paralel-link'], true)}}
                        <span class="verse-text j-verse-text p-medium2" data-version="{!! $code !!}"  data-verseid="{!! $version['verse']->id !!}" style="">
                        {!! ViewHelper::prepareVerseText($version['verse']) !!}
                        </span>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 text-center pagination j-reader-pagination">
            <div class="btn-group" role="group" aria-label="...">
                @if($versePrev = $content['pagination']['versePrev'])
                    {{ Html::link(url('reader/verse?'.http_build_query($versePrev),[]), 'Prev Verse', ['class' => 'btn2','style' => 'min-width:250px; margin-right: 30px'], true)}}
                @endif
                @if($verseNext = $content['pagination']['verseNext'])
                    {{ Html::link(url('reader/verse?'.http_build_query($verseNext),[]), 'Next Verse', ['class' => 'btn1','style' => 'min-width:250px;'], true)}}
                @endif
            </div>
        </div>
    </div>
@stop
