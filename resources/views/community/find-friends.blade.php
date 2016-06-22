@extends('layouts.app')

@section('content')
    {{--<h3 class="text-center">Find Friends</h3>--}}
    <div class="row friends-list j-friends-list">
        <div class="col-md-2">
            @include('community.menu')
        </div>
        <div class="col-md-7" style="line-height: 30px;">
            @role('user')
            <ul class="nav nav-pills friends-nav">
                <li role="presentation" class="{!! (!Request::get('type') || Request::get('type') == 'all')?'active':'' !!}">
                    <a href="{!! url('/community/find-friends?'.http_build_query(array_merge(Request::input(),['type' => 'all']))) !!}">All users</a>
                </li>
                <li role="presentation" class="{!! (Request::get('type') == 'my')?'active':'' !!}">
                    <a href="{!! url('/community/find-friends?'.http_build_query(array_merge(Request::input(),['type' => 'my']))) !!}">Only my friends</a>
                </li>
                <li role="presentation" class="{!! (Request::get('type') == 'new')?'active':'' !!}">
                    <a href="{!! url('/community/find-friends?'.http_build_query(array_merge(Request::input(),['type' => 'new']))) !!}">Only new users</a>
                </li>
            </ul>
            @endrole
            <div>
            @if(count($content['people']))
                @foreach($content['people'] as $people)
                    <div class="clearfix friend-item">
                        <div class="pull-left" style="margin-right: 10px;">
                            @if($people->avatar)
                                <img class="img-thumbnail" height="100" width="100" data-dz-thumbnail="" alt="" src="{!! Config::get('app.userAvatars').$people->id.'/thumbs/'.$people->avatar !!}" />
                            @else
                                <div class="no-avatar img-thumbnail">
                                    <div class="no-avatar-text text-center"><i class="fa fa-user fa-4x"></i></div>
                                </div>
                            @endif
                        </div>
                        <div class="pull-left" style="margin-right: 10px; width: 450px;">
                            <div><strong>{!! $people->name !!}</strong></div>
                            <div style="line-height: 16px; font-size: 12px;">
                                <span style="color:#90949c;">{!! str_limit(strip_tags($people->about_me,'<p></p>'), $limit = 300, $end = '... ') !!}</span>
                            </div>
                        </div>
                        <div>
                            {!! str_limit(strip_tags($people->people_description,'<p></p>'), $limit = 500, $end = '... '.Html::link(url('/peoples/view/'.$people->id,[],false), 'View Details', ['class' => 'btn btn-success','style' => 'padding: 0 5px;'], true)) !!}
                        </div>
                        <div class="pull-right">
                            <a href="{!! url('/user/remove-friend/'.$people->id,[],false) !!}" class="btn btn-danger j-remove-friend {!! in_array($people->id,$myFriends)?'':'hidden' !!}">Unfollow</a>
                            <a href="{!! url('/user/follow-friend/'.$people->id,[],false) !!}" class="btn btn-primary j-follow-friend {!! in_array($people->id,$myFriends)?'hidden':'' !!}">Follow</a>
                            @if(in_array($people->id,$myFriends))
                            @else
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center">No any results found</p>
            @endif
            </div>
            <div class="text-center">
                {!! $content['people']->appends(Request::input())->links() !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Filters</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            @include('community.friends-filters')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
