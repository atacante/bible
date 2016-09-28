@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    {{--    @include('reader.filters')--}}
    <div class="row">
        <div class="col-md-12">
            <div class="c-white-content">
                <div class="inner-pad1">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="col-xs-4">
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
                                                        <div class="product-image-big" data-dz-thumbnail="" data-image="{!!Config::get('app.peopleImages').'thumbs/'.$image->image !!}" style="background: url('{!! $image->image!=''?Config::get('app.peopleImages').'thumbs/'.$image->image:'' !!}') center no-repeat;"></div>
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
                                @else
                                    <div class="product-image-big" data-dz-thumbnail="" data-image="" style="">
                                        <i class="bs-producticon cu-producticon"></i>
                                    </div>
                                @endif


                            </div>

                            <h3 class="h3-kit">{!! $model->people_name !!}</h3>

                            <div class="location-description p-medium mt8">
                                {!! $model->people_description !!}
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <div class="row mt14 mb1">
        <div class="col-xs-12 text-right">
            {!! Html::link((($url = Session::get('backUrl'))?$url:'/peoples/list/'),'Back to list', ['class'=>'btn1 ml1']) !!}
        </div>
    </div>
@stop