<nav class="navbar navbar-default">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a title="Bible" class="navbar-brand" href="/"><i class="bs-biblestudylogo cu-biblestudylogo"></i></a>
    </div>
    <div class="pull-left" style="">
        <ul class="nav navbar-nav">

            {{-- --------------- READ --------------- --}}
            <li class="dropdown">
                <a id="drop1" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="bs-reader"></i>
                    Read
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="drop1">
                    <li><a href="{{ URL::to('/reader/overview') }}">Read</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ URL::to('/locations/list') }}">Locations</a></li>
                    <li><a href="{{ URL::to('/peoples/list') }}">People</a></li>
                </ul>
            </li>

            {{-- --------------- EXPLORE --------------- --}}
            <li class="dropdown">
                <a id="drop1" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="bs-exploer"></i>
                    Explore
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="drop1">
                    <li><a href="{{ URL::to('/') }}">{{--<i class="fa fa-home"></i>--}} Home</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ URL::to('/site/about') }}">About As</a></li>
                    <li><a href="{{ URL::to('/site/faq') }}">FAQ</a></li>
                    <li><a href="{{ URL::to('/site/contact') }}">Contact Us</a></li>
                </ul>
            </li>

            {{-- --------------- COMMUNITY --------------- --}}
            <li class="dropdown">
                <a id="drop1" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="bs-community"></i>
                    Community
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="drop1">
                    <li><a href="{{ URL::to('/community') }}"> Public Wall</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ URL::to('/community/find-friends') }}">Find Friends</a></li>
                    <li><a href="{{ URL::to('/groups') }}">Groups</a></li>
                    <li><a href="{{ URL::to('/blog') }}">Blog</a></li>
                </ul>
            </li>

            <li class="{{ ViewHelper::classActivePath('shop') }}">
                <a href="{{ URL::to('/shop') }}"><i class="fa fa-shopping-cart"></i> Shop</a>
            </li>
        </ul>
    </div>


    @if(Request::segment(1) == 'reader')
        <div class="pull-left" style="width: 270px; margin: 8px 15px 0;">
            {!! Form::open(['method' => 'get','url' => '/reader/search','id' => 'search-verse']) !!}
            {!! Form::text('q',Request::input('q'),['class' => 'pull-left','placeholder' => 'Search verse everywhere...','style' => 'width:220px; margin-right:5px;']) !!}
            {!! Form::submit('Go',['class' => 'btn btn-primary pull-left']) !!}
            {!! Form::close() !!}
        </div>
        <div class="pull-left" style="margin: 14px 10px 0;">
            <div class="radio-inline">
                <label>
                    {!! Form::radio('readerMode', 'beginner', (Request::cookie('readerMode',false) == 'beginner'),['class' => 'j-reader-mode']) !!}
                    {!! Config::get('app.readerModes.beginner') !!}
                </label>
            </div>
            <div class="radio-inline">
                <label>
                    {!! Form::radio('readerMode', 'intermediate', (Request::cookie('readerMode',false) == 'intermediate'),['class' => 'j-reader-mode']) !!}
                    {!! Config::get('app.readerModes.intermediate') !!}
                </label>
            </div>
        </div>
    @endif


    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-right">
            <li class="{{ ViewHelper::classActivePath('shop/cart') }}">
                <a href="{{ URL::to('/shop/cart') }}"><i class="fa fa-shopping-cart"></i> {!! Cart::count() !!} item(s)</a>
            </li>
            @if (Auth::guest())
                <li class="{{ (Request::is('auth/login') ? 'active' : '') }}"><a href="{{ URL::to('auth/login') }}">Login</a></li>
                <li class="{{ (Request::is('auth/register') ? 'active' : '') }} bord-menu-item"><a href="{{ URL::to('auth/register') }}">Register</a></li>
            @else
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="fa fa-btn fa-user"></i>{{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        @role('user')
                        <li><a href="{{ url('user/my-journey') }}"><i class="fa fa-btn fa-location-arrow"></i>My Journey</a></li>
                        <li><a href="{{ url('user/profile') }}"><i class="fa fa-btn fa-user"></i>My Profile</a></li>
                        @endrole
                        <li role="separator" class="divider"></li>
                        <li><a href="{{ url('auth/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                        @if(Session::has('adminAsUser'))
                            <li><a href="{{ url('auth/admin-logout') }}"><i class="fa fa-btn fa-sign-out"></i>Back To Admin</a></li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</nav>