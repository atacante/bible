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
                    <li><a href="{{ URL::to('/reader/read?version=nasb') }}">Read</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ URL::to('/locations/list') }}">Locations</a></li>
                    <li><a href="{{ URL::to('/peoples/list') }}">People</a></li>
                </ul>
            </li>

            {{-- --------------- EXPLORE --------------- --}}
            <li class="dropdown">
                <a id="drop1" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="bs-exploer"></i>
                    People/Places
                    <span class="caret"></span>
                </a>
                <ul class="dropdown-menu" aria-labelledby="drop1">
                    <li><a href="{{ URL::to('/') }}">Home</a></li>
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
        </ul>
    </div>


    @if(Request::segment(1) == 'reader')
        <div class="pull-left c-search1" style="">
            {!! Form::open(['method' => 'get','url' => '/reader/search','id' => 'search-verse']) !!}
            {!! Form::text('q',Request::input('q'),['class' => 'search-text1','placeholder' => 'Search verse everywhere...']) !!}
            {{--{!! Form::submit('<i class="bs-search"></i>',['class' => 'search-btn1']) !!}--}}
            <button class="search-btn1" type="submit"><i class="bs-search"></i></button>
            {!! Form::close() !!}
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
                <li class="{{ (Request::is('auth/login') ? 'active' : '') }}"><a href="{{ URL::to('auth/login') }}">Login</a></li>
                <li class="{{ (Request::is('auth/register') ? 'active' : '') }} bord-menu-item"><a href="{{ URL::to('auth/register') }}">Sign Up</a></li>
            @else

                @role('user')
                <li>
                    <a href="{{ url('user/my-journey') }}"><i class="bs-myjourney"></i>My Journey</a>
                </li>
                @endrole
                <li class="dropdown log-pop">
                    <a href="#" class="dropdown-toggle cu-drop-menu-item" data-toggle="dropdown" role="button" aria-expanded="false">
                        @if(Auth::user()->avatar)
                            <img class="" height="29" width="29" src="{!! Config::get('app.userAvatars').Auth::user()->id.'/thumbs/'.Auth::user()->avatar !!}" />
                        @else
                            <div class="user-default"></div>
                        @endif
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        @role('user')
                        <li>
                            <div class="popup-arrow"></div>
                            {{--<a href="{{ url('user/my-journey') }}">My Journey</a></li>--}}
                        <li><a href="{{ url('user/profile') }}">My Profile</a></li>
                        @endrole
                        {{--<li role="separator" class="divider"></li>--}}
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

{{-- ---------------- Radio Blocks ---------------- --}}
@if(Request::segment(1) == 'reader')
<div class="popup-new j-popup-settings" style="display: none">
    <div class="popup-arrow"></div>

        <div>
            <h4 class="popup-title">
                Settings
            </h4>
            {!!  Form::open(['method' => 'get','url' => '/reader/read']) !!}
            @if(isset($content['showRelated']))
                <div class="mt15">
                    {{--<input type="checkbox">--}}
                    {!! Form::hidden('related', 0) !!}
                    {!! Form::checkbox('related', null, ($content['showRelated'])?1:0) !!}
                    Show Related Records
                </div>
            @endif
            {!! Form::hidden('version', Request::input('version', Config::get('app.defaultBibleVersion'))) !!}
            {!! Form::hidden('book', Request::input('book', Config::get('app.defaultBookNumber'))) !!}
            {!! Form::hidden('chapter', Request::input('chapter', Config::get('app.defaultChapterNumber'))) !!}

            {{--{!! $content['heading'] !!}--}}
            @if($compareVersions = Request::input('compare', false))
                @foreach($compareVersions as $version)
                    {!! Form::hidden('compare[]', $version) !!}
                @endforeach
                {{--{!! link_to('reader/read?'.http_build_query(array_merge(Request::input(),['diff' => Request::input('diff',false)?0:1])), (Request::input('diff',false)?'hide':'show').' diff',['class' => 'btn btn-'.(Request::input('diff',false)?'danger':'success'), 'style' =>'padding: 0 5px;']) !!}--}}
                <div class="mt15">
                    {{--<input type="checkbox">--}}
                    {!! Form::checkbox('diff', null, Request::input('diff',false)?1:0) !!}
                    Show difference
                </div>
            @endif
            @if(isset($content['readerMode']))
            <div class="mt16">
                <div class="radio-inline">
                    <label>
                        {!! Form::radio('readerMode', 'beginner', ($content['readerMode'] == 'beginner'),['class' => 'j-reader-mode']) !!}
                        {!! Config::get('app.readerModes.beginner') !!}
                    </label>
                </div>
                <div class="radio-inline">
                    <label>
                        {!! Form::radio('readerMode', 'intermediate', ($content['readerMode'] == 'intermediate'),['class' => 'j-reader-mode']) !!}
                        {!! Config::get('app.readerModes.intermediate') !!}
                    </label>
                </div>
            </div>
            @endif
            <div class="mt16">
                {!! Form::button('OK', ['type'=>'submit','class'=>'btn1 cu-btn1']) !!}
            </div>
            <?php echo Form::close() ?>
        </div>
</div>
@endif