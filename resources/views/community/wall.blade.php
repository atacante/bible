@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-2">
            <ul class="nav nav-pills nav-stacked nav-left" role="menu">
                <li class="{{ (Request::is('community/wall') || Request::is('community') ? 'active' : '') }}" role="presentation">
                    <a href="{{ url('community/wall') }}"><i class="fa fa-btn fa-tasks"></i>Public Wall</a>
                </li>
                <li role="separator" class="divider" style=""></li>
                <li class="{{ (Request::is('community/groups') ? 'active' : '') }}" role="presentation">
                    <a href="#"><i class="fa fa-btn fa-users"></i>Groups</a>
                </li>
                @role('user')
                <li class="{{ (Request::is('community/groups') ? 'active' : '') }}" role="presentation">
                    <a href="#"><i class="fa fa-btn fa-plus"></i>Create Group</a>
                </li>
                @endrole
                <li role="separator" class="divider" style=""></li>
                <li class="{{ (Request::is('user/friends') ? 'active' : '') }}" role="presentation">
                    <a href="#"><i class="fa-btn ion-person-stalker" style="font-size: 16px;"></i>Friends</a>
                </li>
                <li class="{{ (Request::is('community/blog') ? 'active' : '') }}" role="presentation">
                    <a href="#"><i class="fa fa-btn fa-newspaper-o"></i>Blog</a>
                </li>
            </ul>
        </div>
        <div class="col-md-10 related-records public-wall">
            @include('community.wall-items')
        </div>
    </div>
@endsection
