@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    <div class="row col-md-12">
        @include('reader.filters')
    </div>
    @if(count($content['lexicon']))
        <div class="row col-md-12">
            <h3 class="text-center">KJV Lexicon</h3>
        </div>
        <div class="row col-md-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Verse</th>
                    <th>Symbolism</th>
                    <th>Definition</th>
                    <th>Strong's</th>
                    <th>Transliteration</th>
                </tr>
                </thead>
                <tbody>
                @foreach($content['lexicon'] as $lexiconinfo)
                    <tr>
                        <td>{!! $lexiconinfo->verse_part !!}</td>
                        <td class="j-with-images">{!! $lexiconinfo->symbolism !!}</td>
                        <td>{!! $lexiconinfo->definition !!}</td>
                        <td>{!! link_to('#',$lexiconinfo->strong_num) !!}</td>
                        <td>{!! $lexiconinfo->transliteration !!}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
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
                People{!! ($content['main_verse']['verse']->peoples->count() > 0?'s':'') !!}</h3>
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
    <div class="row col-md-12" style="line-height: 30px;">
        <h4>{{ Html::link(url('reader/read?'.http_build_query(array_merge(Request::input(),['version' => Request::input('version')])),[],false), $content['main_verse']['version_name'], ['class' => '','style' => ''], true)}}</h4>
            <span style="">
                {!! ViewHelper::prepareVerseText($content['main_verse']['verse'],true) !!}
            </span>
    </div>
    <div class="row col-md-12">
        <hr>
    </div>
    <div class="row col-md-12" style="line-height: 30px;">
        @foreach($content['verse'] as $code => $version)
            @if($version['verse'])
                <h4>{{ Html::link(url('reader/read?'.http_build_query(array_merge(Request::input(),['version' => $code])),[],false), $version['version_name'], ['class' => '','style' => ''], true)}}</h4>
                <span style="">
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
