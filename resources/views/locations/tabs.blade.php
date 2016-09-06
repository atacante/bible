
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-pills tabs-nav">
                <li class="{{Request::is('locations/list')?'active':''}}">
                    {!! Html::link('/locations/list','LOCATIONS') !!}
                </li>
                <li class="{{Request::is('peoples/list')?'active':''}}">
                    {!! Html::link('/peoples/list','PEOPLE', ['class'=>(Request::is('peoples/list'))?'active':'']) !!}
                </li>
                <li class="pull-right">
                    @include('locations.filters')
                </li>
            </ul>
        </div>
    </div>