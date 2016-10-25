<div class="bl-header bl-inner">
  <a class="logo" href="/"></a>
  <nav>
    <ul class="main">
      {{-- --------------- HOME --------------- --}}
      <li>
          <a class="bs-home" href="/">Home</a>
      </li>
      {{-- --------------- READ --------------- --}}
      <li class="with-items">
        <a
          class="bs-reader {!! ViewHelper::classActivePath(['reader','peoples','locations']) !!}"
          href="#">Reader</a>
          <ul class="sub-nav">
            <li>
                <a class="bs-reader"
                href="{{ URL::to('/reader/read?version=nasb') }}">Read</a>
            </li>
            <li class="divider"></li>
            <li>
                <a class="bs-lexicon"
                  href="{{ URL::to('/reader/verse?version=nasb&book=1&chapter=1&verse=1') }}">
                  Study Using Lexicon</a>
            </li>
            @if($catId = ViewHelper::getBlogCatId('Study Tools'))
                <li>
                    <a class="bs-study"
                      href="{{ URL::to('/blog?category='.$catId) }}">Study Tools</a>
                </li>
            @endif
            <li>
                <a class="bs-people"
                  href="{{ URL::to('/peoples/list') }}">People</a>
            </li>
            <li>
                <a class="bs-places"
                  href="{{ URL::to('/locations/list') }}">Places</a>
            </li>
          </ul>
      </li>
      {{-- --------------- EXPLORE --------------- --}}
      <li class="with-items">
        <a
          class="bs-exploer {!! ViewHelper::classActivePath(['locations','peoples','site','shop']) !!}"
          href="#">Discover BSC</a>
          <ul class="sub-nav">
            @if(ViewHelper::checkPublished($data['cmsItems'],'bsc_events'))
              <li>
                <a class="bs-calendar"
                  href="{{ URL::to('/site/events') }}">BSC Events</a>
              </li>
            @endif
            @if(ViewHelper::checkPublished($data['cmsItems'],'membership'))
              <li>
                <a class="bs-community"
                  href="{{ URL::to('/site/membership') }}">Membership</a>
              </li>
            @endif
            @if(ViewHelper::checkPublished($data['cmsItems'],'how_it_works'))
              <li>
                <a class="how-it-works fa-question-circle-o"
                  href="{{ URL::to('/site/how-it-works') }}">How It Works</a>
              </li>
            @endif
            @if(ViewHelper::checkPublished($data['cmsItems'],'about'))
              <li>
                <a class="bs-biblestudylogo"
                  href="{{ URL::to('/site/about') }}">About BSC</a>
              </li>
            @endif
            @if(ViewHelper::checkPublished($data['cmsItems'],'recommended_resources'))
              <li>
                <a class="bs-upload"
                  href="{{ URL::to('/site/recommended-resources') }}">Recommended Resources</a>
              </li>
            @endif
            <li>
              <a class="bs-gift" href="{{ URL::to('/shop') }}">Gift Shop</a>
            </li>
            @if(ViewHelper::checkPublished($data['cmsItems'],'partners'))
              <li>
                <a class="bs-community" href="{{ URL::to('/site/partners') }}">Partners</a>
              </li>
            @endif
            @if(ViewHelper::checkPublished($data['cmsItems'],'contact_main'))
              <li>
                <a class="bs-contactus" href="{{ URL::to('/site/contact') }}">Contact Us</a>
              </li>
            @endif
          </ul>
        </li>
      </li>
      <li class="with-items">
        <a
          class="bs-community {!! ViewHelper::classActivePath(['community','groups','blog']) !!}"
          href="#">Community</a>
        <ul class="sub-nav">
          <li>
            <a class="bs-publicwall" href="{{ URL::to('/community') }}">Public Wall</a>
          </li>
          <li role="separator" class="divider"></li>
          <li>
            <a class="bs-s-groups" href="{{ URL::to('/groups') }}">Groups</a>
          </li>
          <li>
            <a class="bs-friends" href="{{ URL::to('/community/find-friends') }}">Friends</a>
          </li>
          <li>
            <a class="bs-blog" href="{{ URL::to('/blog') }}">Blog</a>
          </li>
        </ul>
      </li>
    </ul>

    <ul class="additional">
      @if(Request::is('shop*') || Request::is('order*'))
        <li class="{{ ViewHelper::classActivePath('shop/cart') }}">
          <a href="{{ URL::to('/shop/cart') }}">
            <i class="fa fa-shopping-cart"></i>&nbsp;{!! Cart::count() !!} item(s)</a>
        </li>
      @endif
      @if (Auth::guest())
        <li>
          <a class="{{ (Request::is('auth/login') ? 'active' : '') }}"
            href="{{ URL::to('auth/login') }}">Login</a>
          </li>
        <li>
          <a class="outline {{ (Request::is('auth/register') ? 'active' : '') }}"
            href="{{ URL::to('auth/register') }}">Sign Up</a>
        </li>
      @else
        @role('user')
          <li>
            <a class="bs-myjourney" href="{{ url('user/my-journey') }}">My Journey</a>
          </li>
        @endrole
          <li class="with-items log-pop">
            <a href="#" class="cu-drop-menu-item">
              @if(Auth::user()->avatar)
                <img class="user-avatar" height="30" width="30" src="{!! Config::get('app.userAvatars').Auth::user()->id.'/thumbs/'.Auth::user()->avatar !!}" />
              @else
                <div class="user-default"></div>
              @endif
              <span class="user-menu-name">{{ Auth::user()->name }}</span>
            </a>

            <ul class="sub-nav">
              @role('user')
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

    <a href="#" class="close"></a>
  </nav>
  <a class="show-menu">
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </a>

</div>

<nav id="header" class="navbar navbar-default navbar-inner-pages">
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
@if(Request::segment(1) == 'reader')
    <div class="bl-subnav">
        {!! Form::open(['method' => 'get','url' => '/reader/search','id' => 'search-verse']) !!}
        {!! Form::text('q',Request::input('q'),['class' => 'search-text1','placeholder' => 'Search verse everywhere...']) !!}
        <button class="search-btn1" type="submit"><i class="bs-search"></i></button>
        {!! Form::close() !!}
        {{-- bookmarks list button --}}
        @if(Auth::check())
            <a class="my-bookmarks j-my-bookmarks" href="{{ url('user/my-bookmarks/'.Request::get('version')) }}">{{--<i class="fa fa-bookmark-o" aria-hidden="true"></i>--}}My Bookmarks</a>
        @endif
    </div>
@endif
