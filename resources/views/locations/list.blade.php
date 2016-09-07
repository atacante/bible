@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    {{--    @include('reader.filters')--}}
    @include('locations.tabs')
    <div class="row">
        <div class="col-md-12 locations-list j-locations-list" style="line-height: 30px;">
            <div class="row cu1-row">
            @if(count($content['locations']))
                @foreach($content['locations'] as $location)
                    <div class="my-item">
                        <div class="my-inner-location-item">
                            <div class="">
                                @if($location->images->count())
                                    <div id="location-{!! $location->id !!}" class="carousel slide" data-ride="carousel" data-interval="{!! rand(5000,7000) !!}">
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
                                                        {{--<img src="{!! Config::get('app.locationImages').'thumbs/'.$image->image !!}" class="slider-item-location">--}}
                                                        <div class="people-image" style="background: url('{!! $image->image!=''?Config::get('app.locationImages').'thumbs/'.$image->image:'' !!}') center no-repeat;"></div>
                                                    </div>
                                                @endforeach
                                            </div>

                                            <!-- Controls -->
                                            @if($location->images->count() > 1)
                                                <a class="left carousel-control" href="#location-{!! $location->id !!}"
                                                   role="button" data-slide="prev">
                                                    <span class="glyphicon glyphicon-chevron-left"
                                                          aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <a class="right carousel-control" href="#location-{!! $location->id !!}"
                                                   role="button" data-slide="next">
                                                    <span class="glyphicon glyphicon-chevron-right"
                                                          aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            @endif
                                    </div>
                                @else
                                    <div class="no-image img-thumbnail">
                                        <div class="no-image-text text-center">No image</div>
                                    </div>
                                @endif



                                @if($location->g_map)
                                    <div class="text-center location-map-btn">
                                        {!! Html::link('#', 'View Map', ['class' => 'btn btn-warning j-view-embed-map','style' => 'padding: 0 5px;','data-locationid' => $location->id], true) !!}
                                    </div>
                                @endif
                            </div>
                            <div class="c-inner-location-text">
                                <h4 class="h4-locations">{!! $location->location_name !!}</h4>
                                {!! str_limit(strip_tags($location->location_description,'<p></p>'), $limit = 140, $end = '... <br>'.Html::link(url('/locations/view/'.$location->id,[],false), 'View Details', ['class' => 'btn1 cu2-btn1','style' => 'padding: 0 5px;'], true)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center">No any results found</p>
            @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="text-center">
            {!! $content['locations']->appends(Request::input())->links() !!}
        </div>
    </div>
@stop
