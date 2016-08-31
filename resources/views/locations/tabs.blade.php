
    <div class="row">
        <div class="col-md-12" style="margin-bottom: 30px">
            <div class="location-people" style="height: 35px; border-bottom: 2px solid #cccccc;">
                <div class="pull-left">
                    {!! Html::link('/locations/list','LOCATIONS', ['class'=> (Request::is('locations/list'))?'active medium':'medium'] ) !!}
                    {!! Html::link('/peoples/list','PEOPLE', ['class'=>(Request::is('peoples/list'))?'active medium':'medium']) !!}
                </div>
                <div class="pull-right">
                    @include('locations.filters')
                </div>
            </div>
        </div>
    </div>