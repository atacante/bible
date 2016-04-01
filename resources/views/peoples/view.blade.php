@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    {{--    @include('reader.filters')--}}
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center">{!! $model->people_name !!}</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" style="line-height: 30px;">
            <div class="clearfix people-item-details">
                <div class="pull-left" style="margin-right: 10px;">
                    @if($model->images->count())
                        <div id="people-{!! $model->id !!}" class="carousel slide" data-ride="carousel"
                             data-interval="{!! rand(5000,7000) !!}">
                            <!-- Indicators -->
                            @if($model->images->count() > 1)
                                <ol class="carousel-indicators">
                                    @foreach($model->images as $key => $image)
                                        <li data-target="#people-{!! $model->id !!}"
                                            data-slide-to="{!! $key !!}"
                                            class="{!! ($key == 0?'active':'') !!}"></li>
                                    @endforeach
                                </ol>
                                @endif

                                        <!-- Wrapper for slides -->
                                <div class="carousel-inner" role="listbox">
                                    @foreach($model->images as $key => $image)
                                        <div class="item {!! ($key == 0?'active':'') !!} j-with-images">
                                            <img src="{!! Config::get('app.peopleImages').'thumbs/'.$image->image !!}"
                                                 class="img-thumbnail" alt="" style="cursor: pointer;">
                                            {{--<div class="carousel-caption">--}}
                                            {{--</div>--}}
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Controls -->
                                @if($model->images->count() > 1)
                                    <a class="left carousel-control" href="#people-{!! $model->id !!}"
                                       role="button" data-slide="prev">
                                                <span class="glyphicon glyphicon-chevron-left"
                                                      aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="right carousel-control" href="#people-{!! $model->id !!}"
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
                    {!! $model->people_description !!}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="text-center">
            {!! Html::link((($url = Session::get('backUrl'))?$url:'/peoples/list/'),'Back to list', ['class'=>'btn btn-primary']) !!}
        </div>
    </div>
@stop
