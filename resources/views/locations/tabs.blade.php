
    <div class="row">
        <div class="col-md-12">
            <div class="resp-text-center">
                <ul class="nav nav-pills tabs-nav resp-padd-search">
                    <li class="{{Request::is('locations/list')?'active':''}}">
                        {!! Html::link('/locations/list','LOCATIONS') !!}
                    </li>
                    <li class="{{Request::is('peoples/list')?'active':''}}">
                        {!! Html::link('/peoples/list','PEOPLE', ['class'=>(Request::is('peoples/list'))?'active':'']) !!}
                    </li>
                    <li class="pull-right resp-tab-search">
                        @include(Request::segment(1).'.filters')
                    </li>
                </ul>
            </div>
        </div>
    </div>