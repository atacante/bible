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
    <div class="row">
        <div class="col-xs-12">
            <div class="c-title-and-icons2">
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

                @if($versePrev = $content['pagination']['versePrev'])
                    <a class="genesis-arrow" title="Prev Verse" href="{!! url('reader/verse?'.http_build_query($versePrev),[],false) !!}"><i class="bs-arrowleft cu-arrowleft"></i></a>
                @endif

                <h3 class="h3-kit cu2-title">
                    {!! $content['main_verse']['verse']->booksListEn->book_name.' <span class="fsz1">'.$content['main_verse']['verse']->chapter_num.':'.$content['main_verse']['verse']->verse_num.'</span>' !!}
                </h3>

                @if($verseNext = $content['pagination']['verseNext'])
                    <a class="genesis-arrow" title="Next Verse" href="{!! url('reader/verse?'.http_build_query($verseNext),[],false) !!}"><i class="bs-arrowright cu-arrowright"></i></a>
                @endif

                <div class="btns-panel">
                    <a href="{{ url('/notes/create') }}" class="btn1-kit j-create-note"><i class="bs-add"></i> Note</a>
                    <a href="{{ url('/journal/create') }}" class="btn1-kit j-create-journal"><i class="bs-add"></i> Journal</a>
                    <a href="{{ url('/prayers/create') }}" class="btn1-kit j-create-prayer"><i class="bs-add"></i> Prayer</a>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="c-verse-text-top">
                <div class="c-queries">“</div>
                <h4 class="j-bible-text">
                    <span class="verse-text j-verse-text color4" data-version="{!! $content['main_verse']['version_code'] !!}" data-verseid="{!! $content['main_verse']['verse']->id !!}">{!! ViewHelper::prepareVerseText($content['main_verse']['verse'],true) !!}</span>
                </h4>
            </div>
        </div>
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
                                    <span class="t-label2">Symbolism:</span>
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



    @if($content['main_verse']['verse']->locations->count())
        <div class="row col-md-12">
            <h3 class="text-center">
                Location{!! ($content['main_verse']['verse']->locations->count() > 0?'s':'') !!}</h3>
            @foreach($content['main_verse']['verse']->locations as $location)
                <div class="clearfix location-item">
                    <h4>{!! $location->location_name !!}</h4>
                    <div class="pull-left" style="margin-right: 10px;">
                        @if($location->images->count())
                            <div id="location-{!! $location->id !!}" class="carousel slide" data-ride="carousel"
                                 data-interval="{!! rand(5000,7000) !!}">
                                <!-- Indicators -->
                                @if($location->images->count() > 1)
                                    <ol class="carousel-indicators">
                                        @foreach($location->images as $key => $image)
                                            <li data-target="#location-{!! $location->id !!}"
                                                data-slide-to="{!! $key !!}"
                                                class="{!! ($key == 0?'active':'') !!}"></li>
                                        @endforeach
                                    </ol>
                                    @endif

                                            <!-- Wrapper for slides -->
                                    <div class="carousel-inner" role="listbox">
                                        @foreach($location->images as $key => $image)
                                            <div class="item {!! ($key == 0?'active':'') !!} j-with-images">
                                                <img src="{!! Config::get('app.locationImages').'thumbs/'.$image->image !!}"
                                                     class="img-thumbnail" alt="" style="cursor: pointer;">
                                                {{--<div class="carousel-caption">--}}
                                                {{--</div>--}}
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Controls -->
                                    @if($location->images->count() > 1)
                                        <a class="left carousel-control" href="#location-{!! $location->id !!}"
                                           role="button" data-slide="prev">
                                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="right carousel-control" href="#location-{!! $location->id !!}"
                                           role="button" data-slide="next">
                                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    @endif
                            </div>
                        @endif
                    </div>
                    <div>
                        {!! $location->location_description !!}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    @if($content['main_verse']['verse']->peoples->count())
        <div class="row col-md-12">
            <h3 class="text-center">
                People{{--{!! ($content['main_verse']['verse']->peoples->count() > 0?'s':'') !!}--}}</h3>
            @foreach($content['main_verse']['verse']->peoples as $people)
                <div class="clearfix people-item">
                    <h4>{!! $people->people_name !!}</h4>
                    <div class="pull-left" style="margin-right: 10px;">
                        @if($people->images->count())
                            <div id="people-{!! $people->id !!}" class="carousel slide" data-ride="carousel"
                                 data-interval="{!! rand(5000,7000) !!}">
                                <!-- Indicators -->
                                @if($people->images->count() > 1)
                                    <ol class="carousel-indicators">
                                        @foreach($people->images as $key => $image)
                                            <li data-target="#people-{!! $people->id !!}"
                                                data-slide-to="{!! $key !!}"
                                                class="{!! ($key == 0?'active':'') !!}"></li>
                                        @endforeach
                                    </ol>
                                    @endif

                                            <!-- Wrapper for slides -->
                                    <div class="carousel-inner" role="listbox">
                                        @foreach($people->images as $key => $image)
                                            <div class="item {!! ($key == 0?'active':'') !!} j-with-images">
                                                <img src="{!! Config::get('app.peopleImages').'thumbs/'.$image->image !!}"
                                                     class="img-thumbnail" alt="" style="cursor: pointer;">
                                                {{--<div class="carousel-caption">--}}
                                                {{--</div>--}}
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Controls -->
                                    @if($people->images->count() > 1)
                                        <a class="left carousel-control" href="#people-{!! $people->id !!}"
                                           role="button" data-slide="prev">
                                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="right carousel-control" href="#people-{!! $people->id !!}"
                                           role="button" data-slide="next">
                                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    @endif
                            </div>
                        @endif
                    </div>
                    <div>
                        {!! $people->people_description !!}
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    <div class="row col-md-12">
        <h3 class="text-center">Parallel
            Verses {!! link_to('reader/verse?'.http_build_query(array_merge(Request::input(),['diff' => Request::input('diff',false)?0:1])), (Request::input('diff',false)?'hide':'show').' diff',['class' => 'btn btn-'.(Request::input('diff',false)?'danger':'success'), 'style' =>'padding: 0 5px;']) !!}</h3>
    </div>
    <div class="row col-md-12 j-bible-text" style="line-height: 30px;">
        <h4>{{ Html::link(url('reader/read?'.http_build_query(array_merge(Request::input(),['version' => Request::input('version')])),[],false), $content['main_verse']['version_name'], ['class' => '','style' => ''], true)}}</h4>
            <span class="verse-text j-verse-text" data-version="{!! $content['main_verse']['version_code'] !!}"  data-verseid="{!! $content['main_verse']['verse']->id !!}" style="">
                {!! ViewHelper::prepareVerseText($content['main_verse']['verse'],true) !!}
            </span>
    </div>
    <div class="row col-md-12">
        <hr>
    </div>
    <div class="row col-md-12 j-bible-text" style="line-height: 30px;">
        @foreach($content['verse'] as $code => $version)
            @if($version['verse'])
                <h4>{{ Html::link(url('reader/read?'.http_build_query(array_merge(Request::input(),['version' => $code])),[],false), $version['version_name'], ['class' => '','style' => ''], true)}}</h4>
                <span class="verse-text j-verse-text" data-version="{!! $code !!}"  data-verseid="{!! $version['verse']->id !!}" style="">
                {!! ViewHelper::prepareVerseText($version['verse']) !!}
            </span>
            @endif
        @endforeach
    </div>
    <div class="row col-md-12 pagination" style="text-align: center;">
        <div class="btn-group" role="group" aria-label="...">
            @if($versePrev = $content['pagination']['versePrev'])
                {{ Html::link(url('reader/verse?'.http_build_query($versePrev),[],false), 'Prev Verse', ['class' => 'btn btn-default btn-danger','style' => 'width:120px;'], true)}}
            @endif
            @if($verseNext = $content['pagination']['verseNext'])
                {{ Html::link(url('reader/verse?'.http_build_query($verseNext),[],false), 'Next Verse', ['class' => 'btn btn-default btn-primary','style' => 'width:120px;'], true)}}
            @endif
        </div>
    </div>
@stop
