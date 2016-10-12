<nav id="header" class="navbar navbar-default">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a title="Bible" class="navbar-brand" href="/"><i class="bs-biblestudylogo cu-biblestudylogo"></i><div class="logo-text">BIBLE STUDY CO</div></a>
    </div>

    <div class="collapse navbar-collapse c-menu-home" id="bs-example-navbar-collapse-1">


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
                        <a href="{{ URL::to('/reader/verse?version=nasb&book=1&chapter=1&verse=1') }}">
                            <i class="bs-lexicon cu-lexicon" style="font-size: 14px; vertical-align: baseline;"></i>
                            Study Using Lexicon
                        </a>
                    </li>
                    @if($catId = ViewHelper::getBlogCatId('Study Tools'))
                        <li>
                            <a href="{{ URL::to('/blog?category='.$catId) }}">
                                <i class="bs-study cu-bs-study"></i>
                                Study Tools
                            </a>
                        </li>
                    @endif
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
                        <a href="{{ URL::to('/site/events') }}">
                            <i class="fa fa-calendar cu-nav-icons"></i>
                            BSC Events
                        </a>
                    </li>
                    <li>
                        <a href="{{ URL::to('/site/membership') }}">
                            <i class="bs-community cu-nav-icons"></i>
                            Membership
                        </a>
                    </li>
                    <li>
                        <a href="{{ URL::to('/site/how-it-works') }}">
                            <i class="fa fa-question-circle-o cu-nav-icons" style="font-size: 1.3rem; vertical-align: text-bottom;"></i>
                            How It Works
                        </a>
                    </li>
                    <li>
                        <a href="{{ URL::to('/site/about') }}">
                            <i class="bs-biblestudylogo cu-bs-biblestudylogo"></i>
                            About BSC
                        </a>
                    </li>
                    <li>
                        <a href="{{ URL::to('/site/recommended-resources') }}">
                            <i class="bs-upload cu-bs-biblestudylogo" style="vertical-align: text-bottom;"></i>
                            Recommended Resources
                        </a>
                    </li>
                    <li>
                        <a href="{{ URL::to('/shop') }}">
                            <i class="bs-gift cu-bs-gift"></i>
                            Gift Shop
                        </a>
                    </li>
                    {{--<li>
                        <a href="{{ URL::to('/blog') }}">
                            <i class="bs-blog cu-bs-blog"></i>
                            Blog
                        </a>
                    </li>--}}
                    {{--<li>--}}
                        {{--<a href="{{ URL::to('/site/faq') }}">--}}
                            {{--<i class="bs-faq cu-bs-faq"></i>--}}
                            {{--FAQ--}}
                        {{--</a>--}}
                    {{--</li>--}}
                    <li>
                        <a href="{{ URL::to('/site/contact') }}">
                            <i class="bs-contactus cu-bs-contactus"></i>
                            Contact Us
                        </a>
                    </li>
                </ul>
            </li>

            {{-- --------------- COMMUNITY --------------- --}}
            {{--<li>
                <a class="{!! ViewHelper::classActivePath(['community','groups','blog']) !!}" href="{{ URL::to('/community') }}">
                    <i class="bs-community"></i>
                    Community
                </a>
            </li>--}}
            <li class="dropdown">
                <a id="drop1" href="#" class="dropdown-toggle {!! ViewHelper::classActivePath(['community','groups','blog']) !!}" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="bs-community"></i>
                    Community
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="drop1">
                    <div class="popup-arrow"></div>
                    <li>
                        <a href="{{ URL::to('/community') }}">
                            <i class="bs-publicwall cu-nav-icons"></i>
                            Public Wall
                        </a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li>
                        <a href="{{ URL::to('/groups') }}">
                            <i class="bs-s-groups cu-nav-icons"></i>
                            Groups
                        </a>
                    </li>
                    <li>
                        <a href="{{ URL::to('/community/find-friends') }}">
                            <i class="bs-friends cu-nav-icons"></i>
                            Friends
                        </a>
                    </li>
                    <li>
                        <a href="{{ URL::to('/blog') }}">
                            <i class="bs-blog cu-nav-icons"></i>
                            Blog
                        </a>
                    </li>
                </ul>
            </li>
        </ul>


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
