@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    {{--    @include('reader.filters')--}}
    @include('locations.tabs')
    <div class="row">
        <div class="col-md-12 locations-list" style="line-height: 30px;">
            <div class="row cu1-row">
            @if(count($content['peoples']))
                @foreach($content['peoples'] as $people)
                    <div class="my-item">
                        <div class="my-inner-location-item">

                            <div class="">
                                @if($people->images->count())
                                    <div id="people-{!! $people->id !!}" class="carousel slide" data-ride="carousel" data-interval="{!! rand(5000,7000) !!}">
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
                                                        <div class="people-image" data-image="{!! $image->image!=''?Config::get('app.peopleImages').'thumbs/'.$image->image:'' !!}" style="background: url('{!! $image->image!=''?Config::get('app.peopleImages').'thumbs/'.$image->image:'' !!}') center no-repeat;"></div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Controls -->
                                            @if($people->images->count() > 1)
                                                <a class="left carousel-control" href="#people-{!! $people->id !!}"
                                                   role="button" data-slide="prev">
                                                    <span class="glyphicon glyphicon-chevron-left"
                                                          aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <a class="right carousel-control" href="#people-{!! $people->id !!}"
                                                   role="button" data-slide="next">
                                                    <span class="glyphicon glyphicon-chevron-right"
                                                          aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            @endif
                                    </div>
                                @else
                                    <div class="people-image"></div>
                                @endif
                            </div>
                            <div class="c-inner-location-text">
                                <h4 class="h4-locations">{!! $people->people_name !!}</h4>
                                {!! str_limit(strip_tags($people->people_description,'<p></p>'), $limit = 140, $end = '... '.Html::link(url('/peoples/view/'.$people->id,[]), 'View Details', ['class' => 'btn1 cu2-btn1','style' => 'padding: 0 5px;'], true)) !!}
                            </div>

                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center">No results found</p>
            @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="text-center">
            {!! $content['peoples']->appends(Request::input())->links() !!}
        </div>
    </div>
@stop
