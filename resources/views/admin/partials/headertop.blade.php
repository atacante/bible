<header class="main-header">
    <nav class="navbar navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <a href="/admin" class="navbar-brand"><b>Bible</b> Admin</a>
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="{!! ViewHelper::classActivePath('admin.lexicon') !!}"><a href="{{ url('admin/lexicon/list') }}"><span><i class="ion ion-university"></i> Lexicons</span></a></li>
                    <li class="{!! ViewHelper::classActivePath('admin.bible') !!}"><a href="{{ url('admin/bible/versions') }}"><span><i class="ion ion-ios-book"></i> Bibles</span></a></li>
                    <li class="{!! ViewHelper::classActivePath('admin.user') !!}"><a href="{{ url('admin/user/list') }}"><span><i class="ion ion-person-add"></i> Users</span></a></li>
                    <li class="{!! ViewHelper::classActivePath('admin.location') !!}"><a href="{{ url('admin/location/list') }}"><span><i class="ion ion-ios-location"></i> Locations</span></a></li>
                    <li class="{!! ViewHelper::classActivePath('admin.peoples') !!}"><a href="{{ url('admin/peoples/list') }}"><span><i class="ion ion-ios-people"></i> People</span></a></li>
                    <li class="{!! ViewHelper::classActivePath('admin.coupons') !!}"><a href="{{ url('admin/coupons/list') }}"><span><i class="fa fa-ticket"></i> Coupons</span></a></li>
                    {{--<li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Dropdown <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                            <li class="divider"></li>
                            <li><a href="#">One more separated link</a></li>
                        </ul>
                    </li>--}}
                </ul>
                {{--<form class="navbar-form navbar-left" role="search">
                    <div class="form-group">
                        <input type="text" class="form-control" id="navbar-search-input" placeholder="Search">
                    </div>
                </form>--}}
            </div>
            <!-- /.navbar-collapse -->
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            {{--<img src="{{ asset("/themes/admin-lte/dist/img/user2-160x160.jpg") }}" class="user-image" alt="User Image"/>--}}
                            <i class="fa fa-user"></i>
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs">{{ Auth::user()->name }}</span>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header" style="height: 110px;;">
                                <i class="fa fa-user fa-4x" style="color: #fff;"></i>
                                <p>
                                    {{ Auth::user()->name }}
                                </p>
                            </li>
                            <!-- Menu Body -->
                            {{--<li class="user-body">
                                <div class="col-xs-4 text-center">
                                    <a href="#">Followers</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Sales</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="#">Friends</a>
                                </div>
                            </li>--}}
                                    <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">Profile</a>
                                </div>
                                <div class="pull-right">
                                    <a href="{{ url('auth/logout') }}" class="btn btn-default btn-flat">Sign out</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-custom-menu -->
        </div>
        <!-- /.container-fluid -->
    </nav>
</header>