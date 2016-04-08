@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    {{--    @include('reader.filters')--}}
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center">Locations</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
                <div class="box-header" style="height: 44px;">
                    <h3 class="box-title">Filters</h3>
                    <div class="box-tools">
                        <div class="pull-right">
                            @include('locations.filters')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="line-height: 30px;">
            @if(count($content['locations']))
                @foreach($content['locations'] as $location)
                    <div class="clearfix location-item">
                        <h4>{!! $location->location_name !!}</h4>
                        <div class="pull-left" style="margin-right: 10px;">
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
                            @endif
                        </div>
                        <div>
                            {!! str_limit(strip_tags($location->location_description,'<p></p>'), $limit = 500, $end = '... '.Html::link(url('/locations/view/'.$location->id,[],false), 'View Details', ['class' => 'btn btn-success','style' => 'padding: 0 5px;'], true)) !!}
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center">No any results found</p>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="text-center">
            {!! $content['locations']->appends(Request::input())->links() !!}
        </div>
    </div>
@stop
