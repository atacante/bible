<nav class="navbar navbar-default navbar-inner-pages">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle Navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a title="Bible" class="navbar-brand" href="/"><i class="bs-biblestudylogo cu-biblestudylogo"></i></a>
    </div>

    <div class="collapse navbar-collapse c-menu-home" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav">
            {{-- --------------- HOME --------------- --}}
            <li>
                <a href="/"><i class="bs-home"></i>Home</a>
            </li>
            {{-- --------------- READ --------------- --}}
            <li class="dropdown">
                <a id="drop1" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
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
                            <i class="bs-lexicon cu-lexicon" style="font-size: 13px; vertical-align: baseline;"></i>
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
                <a class="{!! ViewHelper::classActivePath(['site','shop']) !!}" id="drop1" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="bs-exploer"></i>
                    Discover BSC
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="drop1">
                    <div class="popup-arrow"></div>
                    @if(ViewHelper::checkPublished($data['cmsItems'],'bsc_events'))
                        <li>
                            <a href="{{ URL::to('/site/events') }}">
                                <i class="bs-calendar cu-nav-icons"></i>
                                BSC Events
                            </a>
                        </li>
                    @endif
                    @if(ViewHelper::checkPublished($data['cmsItems'],'membership'))
                        {{--<li>
                            <a href="{{ URL::to('/site/membership') }}">
                                <i class="bs-community cu-nav-icons"></i>
                                Membership
                            </a>
                        </li>--}}
                    @endif
                    @if(ViewHelper::checkPublished($data['cmsItems'],'how_it_works'))
                    <li>
                        <a href="{{ URL::to('/site/how-it-works') }}">
                            <i class="fa fa-question-circle-o cu-nav-icons" style="font-size: 1.3rem; vertical-align: top;"></i>
                            How It Works
                        </a>
                    </li>
                    @endif
                    @if(ViewHelper::checkPublished($data['cmsItems'],'about'))
                    <li>
                        <a href="{{ URL::to('/site/about') }}">
                            <i class="bs-biblestudylogo cu-bs-biblestudylogo"></i>
                            About BSC
                        </a>
                    </li>
                    @endif
                    @if(    ViewHelper::checkPublished($data['cmsItems'],'recommended_resources')
                        && ($catId = ViewHelper::getBlogCatId('Recommended Resources'))
                    )
                    <li>
                        <a href="{{ URL::to('/blog?category='.$catId) }}">
                            <i class="bs-upload cu-bs-biblestudylogo" style="vertical-align: bottom;"></i>
                            Recommended Resources
                        </a>
                    </li>
                    @endif
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
                    @if(ViewHelper::checkPublished($data['cmsItems'],'faq'))
                        <li>
                            <a href="{{ URL::to('/site/faq') }}">
                                <i class="bs-faq cu-nav-icons" style="font-size: 1.3rem; vertical-align: top;"></i>
                                F.A.Q.
                            </a>
                        </li>
                    @endif
                    @if(ViewHelper::checkPublished($data['cmsItems'],'partners'))
                    {{--<li>
                        <a href="{{ URL::to('/site/partners') }}">
                            <i class="bs-community cu-nav-icons"></i>
                            Partners
                        </a>
                    </li>--}}
                    @endif
                    @if(ViewHelper::checkPublished($data['cmsItems'],'contact_main'))
                    <li>
                        <a href="{{ URL::to('/site/contact') }}">
                            <i class="bs-contactus cu-bs-contactus"></i>
                            Contact Us
                        </a>
                    </li>
                    @endif
                </ul>
            </li>

            {{-- --------------- COMMUNITY --------------- --}}
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
                            <i class="bs-publicwall"></i>
                            Public Wall
                        </a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li>
                        <a href="{{ URL::to('/groups') }}">
                            <i class="bs-s-groups"></i>
                            Groups
                        </a>
                    </li>
                    <li>
                        <a href="{{ URL::to('/community/find-friends') }}">
                            <i class="bs-friends"></i>
                            Friends
                        </a>
                    </li>
                    @if(    ViewHelper::checkPublished($data['cmsItems'],'recommended_resources')
                        && ($catId = ViewHelper::getBlogCatId('Recommended Resources'))
                    )
                        <li>
                            <a href="{{ URL::to('/blog?category='.$catId) }}">
                                <i class="bs-blog"></i>
                                Blog
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            @if(Request::segment(1) == 'reader')
                <li class="pull-left c-search1" style="">
                    {!! Form::open(['method' => 'get','url' => '/reader/search','id' => 'search-verse']) !!}
                    {!! Form::text('q',Request::input('q'),['class' => 'search-text1','placeholder' => 'Search verse everywhere...']) !!}
                    <button class="search-btn1" type="submit"><i class="bs-search"></i></button>
                    {!! Form::close() !!}
                </li>
            @endif
        </ul>

        <ul class="nav navbar-nav navbar-right">

            @if (Auth::guest())
                <li><a class="{{ (Request::is('auth/login') ? 'active' : '') }}" href="{{ URL::to('auth/login') }}">Login</a></li>
                <li class=" bord-menu-item"><a class="{{ (Request::is('auth/register') ? 'active' : '') }}" href="{{ URL::to('auth/register') }}">Sign Up</a></li>
            @else

                @role('user')
                    <li>
                        <a class="my-journey {{ ViewHelper::classActivePath('user.my-journey') }}" href="{{ url('user/my-journey') }}"><i class="bs-myjourney"></i>My Journey</a>
                    </li>
                @endrole
                <li class="dropdown log-pop">
                    <a href="#" class="dropdown-toggle cu-drop-menu-item" data-toggle="dropdown" role="button" aria-expanded="false">
                        @if(Auth::user()->avatar)
                            <img class="user-avatar" height="29" width="29" src="{!! Config::get('app.userAvatars').Auth::user()->id.'/thumbs/'.Auth::user()->avatar !!}" />
                        @else
                            <div class="user-default"></div>
                        @endif
                        <span title="{{ Auth::user()->name }}" class="user-neme-cut">{{ Auth::user()->name }}</span> <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        @role('user')
                        <li>
                            <div class="popup-arrow"></div>
                        <li><a href="{{ url('user/profile') }}">My Profile</a></li>
                        @endrole
                        <li><a href="{{ url('auth/logout') }}">Logout</a></li>
                        @role('administrator')
                            <li><a href="{{ url('admin') }}">Admin</a></li>
                        @endrole
                        @if(Session::has('adminAsUser'))
                            <li><a href="{{ url('auth/admin-logout') }}">Back To Admin</a></li>
                        @endif
                    </ul>
                </li>
            @endif
        </ul>
    </div>
</nav>
<div class="row">
    <div class="col-xs-12">
        {{-- bookmarks list button --}}
        @if(Auth::check() && Request::segment(1) == 'reader')
            <a class="my-bookmarks j-my-bookmarks" href="{{ url('user/my-bookmarks/'.Request::get('version')) }}">{{--<i class="fa fa-bookmark-o" aria-hidden="true"></i>--}}My Bookmarks</a>
        @endif
    </div>
</div>


