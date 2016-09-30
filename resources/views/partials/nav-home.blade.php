<nav class="navbar navbar-default">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a title="Bible" class="navbar-brand" href="/"><i class="bs-biblestudylogo cu-biblestudylogo"></i><div class="logo-text">BIBLE STUDY CO</div></a>
    </div>
    <div class="pull-left" style="">
        <ul class="nav navbar-nav">

            {{-- --------------- READ --------------- --}}
            <li class="dropdown">
                <a id="drop1" href="#" class="dropdown-toggle {!! ViewHelper::classActivePath(['reader','peoples','locations']) !!}" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="bs-reader"></i>
                    Reader
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="drop1">
                    <div class="popup-arrow"></div>
                    <li>
                        <a href="{{ URL::to('/reader/read?version=nasb') }}">
                            <i class="bs-reader"></i>Read
                        </a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li>
                        <a href="{{ URL::to('/peoples/list') }}">
                            <i class="bs-people cu-bs-people"></i>
                            People
                        </a>
                    </li>
                    <li>
                        <a href="{{ URL::to('/locations/list') }}">
                            <i class="bs-places cu-bs-places"></i>
                            Places
                        </a>
                    </li>
                </ul>
            </li>

            {{-- --------------- EXPLORE --------------- --}}
            <li class="dropdown">
                <a class="{!! ViewHelper::classActivePath(['locations','peoples','site','shop']) !!}" id="drop1" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="bs-exploer"></i>
                    Discover BSC
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="drop1">
                    <div class="popup-arrow"></div>
                    <li>
                        <a href="{{ URL::to('/site/about') }}">
                            <i class="bs-biblestudylogo cu-bs-biblestudylogo"></i>
                            About Us
                        </a>
                    </li>
                    <li>
                        <a href="{{ URL::to('/shop') }}">
                            <i class="bs-gift cu-bs-gift"></i>
                            Gift Shop
                        </a>
                    </li>
                    <li>
                        <a href="{{ URL::to('/site/faq') }}">
                            <i class="bs-faq cu-bs-faq"></i>
                            FAQ
                        </a>
                    </li>
                    <li>
                        <a href="{{ URL::to('/site/contact') }}">
                            <i class="bs-contactus cu-bs-contactus"></i>
                            Contact Us
                        </a>
                    </li>
                </ul>
            </li>

            {{-- --------------- COMMUNITY --------------- --}}
            <li>
                <a class="{!! ViewHelper::classActivePath(['community','groups','blog']) !!}" href="{{ URL::to('/community') }}">
                    <i class="bs-community"></i>
                    Community
                </a>
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
            @if(Request::is('shop*') || Request::is('order*'))
                <li class="{{ ViewHelper::classActivePath('shop/cart') }}">
                    <a href="{{ URL::to('/shop/cart') }}"><i class="fa fa-shopping-cart"></i> {!! Cart::count() !!} item(s)</a>
                </li>
            @endif
            @if (Auth::guest())
                <li><a  class="{{ (Request::is('auth/login') ? 'active' : '') }}" href="{{ URL::to('auth/login') }}">Login</a></li>
                <li class=" bord-menu-item"><a class="{{ (Request::is('auth/register') ? 'active' : '') }}" href="{{ URL::to('auth/register') }}">Sign Up</a></li>
            @else
                @role('user')
                <li>

                    <a href="{{ url('user/my-journey') }}"><i class="bs-myjourney"></i>My Journey</a>
                </li>
                @endrole
                <li class="dropdown log-pop">
                    <a href="#" class="dropdown-toggle cu-drop-menu-item" data-toggle="dropdown" role="button" aria-expanded="false">
                        @if(Auth::user()->avatar)
                            <img class="user-avatar" height="30" width="30" src="{!! Config::get('app.userAvatars').Auth::user()->id.'/thumbs/'.Auth::user()->avatar !!}" />
                        @else
                            <div class="user-default"></div>
                        @endif
                        <span class="user-menu-name">{{ Auth::user()->name }}</span> <span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        @role('user')
                        <li>
                            <div class="popup-arrow"></div>
                        </li>
                        <li><a href="{{ url('user/profile') }}">My Profile</a></li>
                        @endrole
                        <li><a href="{{ url('auth/logout') }}">Logout</a></li>
                        @if(Session::has('adminAsUser'))
                            <li><a href="{{ url('auth/admin-logout') }}">Back To Admin</a></li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</nav>