<div class="bl-header">
  <a class="logo" href="/">BIBLE STUDY COMPANY</a>
  <nav>
    <ul class="main">
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
      @if(Request::segment(1) == 'reader')
        <li class="pull-left c-search1" style="">
          {!! Form::open(['method' => 'get','url' => '/reader/search','id' => 'search-verse']) !!}
          {!! Form::text('q',Request::input('q'),['class' => 'search-text1','placeholder' => 'Search verse everywhere...']) !!}
          <button class="search-btn1" type="submit"><i class="bs-search"></i></button>
          {!! Form::close() !!}
        </li>
      @endif
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
<div class="c-search1">
    {!! Form::open(['method' => 'get','url' => '/reader/search','id' => 'search-verse']) !!}
    {!! Form::text('q',Request::input('q'),['class' => 'search-text1','placeholder' => 'Search verse everywhere...']) !!}
    <button class="search-btn1" type="submit"><i class="bs-search"></i></button>
    {!! Form::close() !!}
</div>
