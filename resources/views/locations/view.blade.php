@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    {{--    @include('reader.filters')--}}
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center">{!! $model->location_name !!}</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="line-height: 30px;">
            <div class="clearfix location-item-details">
                <div class="pull-left" style="margin-right: 10px;">
                    @if($model->images->count())
                        <div id="location-{!! $model->id !!}" class="carousel slide" data-ride="carousel"
                             data-interval="{!! rand(5000,7000) !!}">
                            <!-- Indicators -->
                            @if($model->images->count() > 1)
                                <ol class="carousel-indicators">
                                    @foreach($model->images as $key => $image)
                                        <li data-target="#location-{!! $model->id !!}"
                                            data-slide-to="{!! $key !!}"
                                            class="{!! ($key == 0?'active':'') !!}"></li>
                                    @endforeach
                                </ol>
                                @endif

                                        <!-- Wrapper for slides -->
                                <div class="carousel-inner" role="listbox">
                                    @foreach($model->images as $key => $image)
                                        <div class="item {!! ($key == 0?'active':'') !!} j-with-images">
                                            <img src="{!! Config::get('app.locationImages').'thumbs/'.$image->image !!}"
                                                 class="img-thumbnail" alt="" style="cursor: pointer;">
                                            {{--<div class="carousel-caption">--}}
                                            {{--</div>--}}
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Controls -->
                                @if($model->images->count() > 1)
                                    <a class="left carousel-control" href="#location-{!! $model->id !!}"
                                       role="button" data-slide="prev">
                                                <span class="glyphicon glyphicon-chevron-left"
                                                      aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="right carousel-control" href="#location-{!! $model->id !!}"
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
                </div>
                @if($model->g_map)
                    <div class="pull-right g-map-embed">
                        {!! $model->g_map !!}
                    </div>
                @endif
                <div>
                    {!! $model->location_description !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="text-center">
            {!! Html::link((($url = Session::get('backUrl'))?$url:'/locations/list/'),'Back to list', ['class'=>'btn btn-primary']) !!}
        </div>
    </div>
@stop
