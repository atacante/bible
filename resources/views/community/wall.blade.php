@extends('layouts.app')

@section('content')
    <div class="row wall">
        <div class="col-md-3">
            @include('community.menu')
        </div>
        <div class="col-md-9 related-records public-wall j-wall-items">

            @role('user')
            <ul class="nav nav-pills wall-nav">
                <li role="presentation" class="{!! (!Request::get('type') || Request::get('type') == 'all')?'active':'' !!}">
                    <a href="{!! url('/community/wall?type=all') !!}">All public records</a>
                </li>
                <li role="presentation" class="{!! (Request::get('type') == 'friends')?'active':'' !!}">
                    <a href="{!! url('/community/wall?type=friends') !!}">My friends records</a>
                </li>
            </ul>
            @endrole
            <div class="c-white-content">
                @role('user')
                    @if((!Request::get('type') || Request::get('type') == 'all'))
                        @include('wall-posts.status-form',['wallType' => App\WallPost::WALL_TYPE_PUBLIC])
                    @endif
                @endrole
                @include('community.wall-items')
            </div>
        </div>
    </div>
@endsection
