<nav class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Bible Reader</a>
        </div>
        <div class="pull-left" style="">
            <ul class="nav navbar-nav">
                <li class="{{ ViewHelper::classActivePath('locations') }}">
                    <a href="{{ URL::to('/locations/list') }}"><i class="fa fa-map-marker"></i> Locations</a>
                </li>
                <li class="{{ ViewHelper::classActivePath('peoples') }}">
                    <a href="{{ URL::to('/peoples/list') }}"><i class="fa fa-users"></i> People</a>
                </li>
            </ul>
        </div>
        <div class="pull-left" style="width: 450px; margin: 8px 15px 0;">
            {!! Form::open(['method' => 'get','url' => '/reader/search','id' => 'search-verse']) !!}
            {!! Form::text('q',Request::input('q'),['class' => 'pull-left','placeholder' => 'Search verse everywhere...','style' => 'width:400px; margin-right:5px;']) !!}
            {!! Form::submit('Go',['class' => 'btn btn-primary pull-left']) !!}
            {!! Form::close() !!}
        </div>
        <div class="pull-left" style="margin: 8px 10px 0;">
{{--            {!! Form::label('readerMode', 'Reader Mode',['class' => 'pull-left','style' => 'line-height: 35px; margin-right: 10px;']) !!}--}}
            {!! Form::select('readerMode',Config::get('app.readerModes'), (($mode = Request::cookie('readerMode',false))?$mode:Config::get('app.defaultReaderMode')),['title' => 'Reader Mode','class' => 'pull-left j-reader-mode', 'style' => 'width: 135px; margin-right:10px;']) !!}
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                @if (Auth::guest())
                    <li class="{{ (Request::is('auth/login') ? 'active' : '') }}"><a href="{{ URL::to('auth/login') }}"><i
                                    class="fa fa-sign-in"></i> Login</a></li>
                    <li class="{{ (Request::is('auth/register') ? 'active' : '') }}"><a
                                href="{{ URL::to('auth/register') }}">Register</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            @role('user')
                            <li><a href="{{ url('notes/list') }}"><i class="fa fa-btn fa-sticky-note"></i>My Notes</a></li>
                            <li><a href="{{ url('user/profile') }}"><i class="fa fa-btn fa-user"></i>My Profile</a></li>
                            @endrole
                            <li><a href="{{ url('auth/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            @if(Session::has('adminAsUser'))
                            <li><a href="{{ url('auth/admin-logout') }}"><i class="fa fa-btn fa-sign-out"></i>Back To Admin</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>