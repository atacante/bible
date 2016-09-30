{{--{!! var_dump($lexiconinfo) !!}--}}
@if(Request::input('compare',false))
    <div class="popup-arrow3"></div>
    <div class="row">
        <div class="col-md-12 font-size-22">
            <i class="bs-lexicon"></i> <span class="medium">LEXICON - </span></br>
            <i>"{!! strtoupper($lexiconinfo->verse_part) !!}"</i>
        </div>
    </div>
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-12 medium">SYMBOLISM</div>
        <div class="col-md-12">{!! $lexiconinfo->symbolism?$lexiconinfo->symbolism:'-' !!}</div>
    </div>

    @if($lexiconinfo->locations->count())
        <hr class="mt0"/>
        <div class="row">
            <div class="col-md-12 medium">LOCATIONS</div>
            <div class="col-md-12">
                <div class="text-left">
                    @foreach($lexiconinfo->locations as $location)
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
            </div>
        </div>
    @endif

    @if($lexiconinfo->peoples->count())
        <hr class="mt0"/>
        <div class="row">
            <div class="col-md-12 medium">PEOPLE</div>
            <div class="col-md-12">
                <div class="row col-md-12">
                    @foreach($lexiconinfo->peoples as $people)
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
            </div>
        </div>
    @endif
    <a class="btn-reset j-btn-reset">x</a>
@else
    <div class="popup-arrow3"></div>
    <div class="row">
        <div class="col-md-12 font-size-22"><i class="bs-lexicon"></i> <span class="medium">LEXICON - </span><i>"{!! strtoupper($lexiconinfo->verse_part) !!}"</i></div>
    </div>
    <hr class="mt0"/>
    <div class="row">
        <div class="col-md-1 mr4p medium">ANALYSIS</div>
        <div class="col-md-10">{!! $lexiconinfo->symbolism?$lexiconinfo->symbolism:'-' !!}</div>
    </div>

    @if($lexiconinfo->locations->count())
        <hr class="mt0"/>
        <div class="row">
            <div class="col-md-1 mr4p medium">LOCATIONS</div>
            <div class="col-md-10">
                <div class="text-left">
                    @foreach($lexiconinfo->locations as $location)
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
            </div>
        </div>
    @endif

    @if($lexiconinfo->peoples->count())
        <hr class="mt0"/>
        <div class="row">
            <div class="col-md-1 mr4p medium">PEOPLE</div>
            <div class="col-md-10">
                <div class="row col-md-12">
                    @foreach($lexiconinfo->peoples as $people)
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
            </div>
        </div>
    @endif
    <a class="btn-reset j-btn-reset">x</a>
@endif