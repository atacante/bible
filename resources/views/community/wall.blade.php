@extends('layouts.app')

@section('content')
    <div class="row wall">
        <div class="col-md-3">
            @include('community.menu')
        </div>
        <div class="col-md-9 public-wall">
            @role('user')
            <ul class="nav nav-pills tabs-nav">
                <li role="presentation" class="{!! (!Request::get('type') || Request::get('type') == 'all')?'active':'' !!}">
                    <a href="{!! url('/community/wall?type=all') !!}">All public records</a>
                </li>
                <li role="presentation" class="{!! (Request::get('type') == 'friends')?'active':'' !!}">
                    <a href="{!! url('/community/wall?type=friends') !!}">My friends records</a>
                </li>
            </ul>
            @endrole
            <div class="c-white-content j-wall-items">
                @role('user')
                    @if((!Request::get('type') || Request::get('type') == 'all'))
                        @include('wall-posts.status-form',['wallType' => App\WallPost::WALL_TYPE_PUBLIC])
                    @endif
                @endrole
                @include('community.wall-items')
            </div>
            @if( $content['nextPage'])
                <div class="load-more-block mt3 mb1">
                    <div class="text-center">
                        {!! Html::link('/community/wall?'.http_build_query(
                            array_merge(Request::input(),['page' => $content['nextPage']])
                        ),'Load More', ['class'=>'btn1 load-more','style' => 'width:100%;']) !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
