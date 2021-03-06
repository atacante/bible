<div style="max-width: 400px; max-height: 300px; overflow-y: auto;">
    {!! $lexiconinfo->symbolism?$lexiconinfo->symbolism:'No analysis info' !!}
    <div class="clearfix"></div>
    @if($lexiconinfo->locations->count())
        <div class="text-left">
            <h3 class="text-left">Location{!! ($lexiconinfo->locations->count() > 0?'s':'') !!}</h3>
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
    @endif
    @if($lexiconinfo->peoples->count())
        <div class="row col-md-12">
            <h3 class="text-center">
                People{{--{!! ($lexiconinfo->peoples->count() > 0?'s':'') !!}--}}</h3>
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
    @endif
</div>
