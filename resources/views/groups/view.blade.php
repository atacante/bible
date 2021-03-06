@extends('layouts.app')

@section('content')
    <div class="row wall">
        <div class="col-md-3">
            @include('community.menu')
            <div class="c-white-content group-info mt13 mb1">
                <div class="inner-pad3">
                    <div class="clearfix info-block">
                        <div class="widget-group-title">
                            {!! $model->group_name !!}
                            @if($model->checkAccess())

                                <a class="count-members" href="{!! url('/groups/view/'.$model->id.'?p=members') !!}">
                                    <i class="bs-friends"></i>
                                    {!! $model->members->count()+1 !!}
                                </a>
                            @endif
                        </div>

                        <div class="c-description-w">
                            {!! nl2br($model->group_desc) !!}
                        </div>

                        @if($model->checkAccess())
                        <div>
                            <div class="widget-group-title2">
                                Members
                            </div>
                            @foreach($content['membersSample'] as $member)
                                <a href="#" title="{!! $member->name !!}"  class="friend-item">
                                    <div class="pull-left" style="margin: 0 2px 2px 0;">
                                        @if($member->avatar)
                                            <img class="img-thumbnail-mini2" height="30" width="30" data-dz-thumbnail="" alt="" src="{!! Config::get('app.userAvatars').$member->id.'/thumbs/'.$member->avatar !!}"/>
                                        @else
                                            <div class="no-avatar-mini2 img-thumbnail-mini2">
                                                <div class="no-avatar-text text-center"><i class="fa fa-user" style="font-size:24px;"></i></div>
                                            </div>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @if((Auth::user() && $model->owner_id == Auth::user()->id))
                        <div class="widget-group">
                            <div class="widget-group-title2">
                                Invite Members
                            </div>
                            <div>
                                {!! Form::open(['method' => 'post','url' => '/groups/request-users', 'id' => 'request-users-form', 'class' => '','role' => 'form','files' => true]) !!}
                                {!! Form::hidden('group_id', $model->id) !!}
                                {!! Form::select('users[]', $content['membersToRequest'], [],['placeholder' => 'Enter user name...','multiple' => true,'class' => 'clear-fix j-select2', 'style' => '']) !!}
                                {!! Form::button('Send', ['type'=>'submit','class'=>'btn1-kit mt16']) !!}
                                {!! Form::close() !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>


        {{-- -------- COLUMN RIGHT ---------  --}}
        <div class="col-md-9">
            <div class="group-details" style="background: url('{!!  $model->group_image?Config::get('app.groupImages').$model->id.'/'.$model->group_image:'/images/nocatimg-m.jpg' !!}'); {!! $model->group_image?'background-size: 100%;':'' !!} background-position: center center;">
                <div class="header" style="">
                    <h3 class="title">
                        <div class="row">
                            <div class="col-lg-6 col-md-4 col-xs-12">
                                <i class="bs-s-groups"></i>{!! $model->group_name !!}
                            </div>
                            <div class="controls col-lg-6 col-md-8 col-xs-12">
                                <div class="btns-panel" style="right: 15px;">
                                    <a href="{{ url('/notes/create') }}" class="j-create-note btn1-kit j-create-note"><i class="bs-add"></i> Note</a>
                                    <a href="{{ url('/journal/create') }}" class="j-create-journal btn1-kit j-create-journal"><i class="bs-add"></i> Journal</a>
                                    <a href="{{ url('/prayers/create') }}" class="j-create-prayer btn1-kit j-create-prayer"><i class="bs-add"></i> Prayer</a>
                                </div>
                                @if((Auth::user() && $model->owner_id == Auth::user()->id))
                                    <div class="group-controls pull-right">
                                        <a title="Delete group" href="{!! url('/groups/delete/'.$model->id,[]) !!}" class="pull-right j-remove-group" data-toggle="modal"
                                           data-target="#confirm-delete" data-header="Delete Confirmation"
                                           data-confirm="Are you sure you want to delete this item?"><i class=" bs-remove"></i></a>
                                        <a href="{!! url('/groups/update/'.$model->id,[]) !!}" class="pull-right"><i class="bs-settings"></i></a>
                                    </div>
                                @else
                                    <div class="group-controls pull-right" title="{!! Auth::user() && Auth::user()->isPremium() || in_array($model->id,$content['joinedGroupsKeys'])?'':'Premium Feature' !!} {!! Auth::user() && Auth::user()->isBanned('group',$model->id)?'You were banned from being part of this group':'' !!}">
                                        @if(Auth::check())
                                            <a title="Cancel Request" href="{!! url('/groups/cancel-request/'.$model->id.'/'.Auth::user()->id,[]) !!}" class="btn1-kit j-cancel-request {{(Auth::check() && in_array($model->id,Auth::user()->myGroupsRequests->modelKeys()))?'':'hidden'}}" data-toggle="modal"
                                               data-target="#cancel-request-sm" data-header="Cancel Request"
                                               data-confirm="Are you sure you want to cancel this request?">
                                                <i class="bs-close cu-btn-ic"></i>
                                            </a>
                                        @endif
                                        <a href="{!! url('/groups/leave-group/'.$model->id,[]) !!}" class="btn1-kit j-leave-group {!! in_array($model->id,$content['joinedGroupsKeys'])?'':'hidden' !!}">Leave Group</a>
                                        <a href="{!! url('/groups/join-group/'.$model->id,[]) !!}" class="btn1-kit j-join-group {!! in_array($model->id,$content['joinedGroupsKeys']) || (Auth::check() && in_array($model->id,Auth::user()->myGroupsRequests->modelKeys()))?'hidden':'' !!} {!! Auth::user() && Auth::user()->isPremium()?'':'disabled' !!} {!! Auth::user() && Auth::user()->isBanned('group',$model->id)?'disabled':'' !!}">Join Group</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
            {{--@role('user')--}}
            <div>
                <ul class="nav nav-pills tabs-nav">
                    <li role="presentation" class="{!! !Request::get('p')?'active':'' !!}">
                        <a href="{!! url('/groups/view/'.$model->id) !!}">Feed</a>
                    </li>
                    @if($model->checkAccess())
                    <li role="presentation" class="{!! (Request::get('p') == 'members' && !Request::get('type'))?'active':'' !!}">
                        <a href="{!! url('/groups/view/'.$model->id.'?p=members') !!}">Members</a>
                    </li>
                    @endif
                    @if((Auth::user() && $model->owner_id == Auth::user()->id))
                        <li role="presentation" class="{!! (Request::get('p') == 'requests')?'active':'' !!}">
                            <a href="{!! url('/groups/view/'.$model->id.'?p=requests') !!}">Requests</a>
                        </li>
                    @endif
                    @if((Auth::user() && $model->owner_id == Auth::user()->id))
                    <li role="presentation" class="{!! (Request::get('p') == 'invitations')?'active':'' !!}">
                        <a href="{!! url('/groups/view/'.$model->id.'?p=invitations') !!}">Invitations</a>
                    </li>
                    @endif
                    @if((Auth::user() && $model->owner_id == Auth::user()->id))
                        <li role="presentation" class="{!! (Request::get('p') == 'members' && Request::get('type') == 'banned')?'active':'' !!}">
                            <a href="{!! url('/groups/view/'.$model->id.'?p=members&type=banned') !!}">Bans</a>
                        </li>
                    @endif
                </ul>
            </div>
            {{--@endrole--}}

                @if(Request::get('p') == 'members')
                    @if($model->checkAccess())
                        <div class="row cu1-row">
                            <div class="col-xs-12 related-records j-members-list">
                                <div class="row">
                                    @include('groups.members')
                                </div>
                            </div>
                        </div>
                    @endif
                @elseif(Request::get('p') == 'invitations')
                    <div class="row cu1-row">
                        <div class="col-xs-12 related-records j-members-list">
                            <div class="row">
                                @include('groups.invitations')
                            </div>
                        </div>
                    </div>
                @elseif(Request::get('p') == 'requests')
                    <div class="row cu1-row">
                        <div class="col-xs-12 related-records j-members-list">
                            <div class="row">
                                @include('groups.requests')
                            </div>
                        </div>
                    </div>
                @else
                    <div class="row">
                        <div class="col-xs-12 group-wall">
                            <div class="c-white-content related-records public-wall j-wall-items" data-walltype="{{App\WallPost::WALL_TYPE_GROUP}}">
                                @if(Auth::check() && Auth::user()->is('user') && $model->isUserInGroup())
                                    @include('wall-posts.status-form',['wallType' => App\WallPost::WALL_TYPE_GROUP,'groupId' => $model->id])
                                @endif
                                @include('community.wall-items')
                            </div>
                            @if($model->checkAccess() && $content['nextPage'])
                                <div class="load-more-block mt3 mb1">
                                    <div class="text-center">
                                        {!! Html::link('/groups/view/'.$model->id.'?'.http_build_query(
                                            array_merge(Request::input(),['page' => $content['nextPage']])
                                        ),'Load More', ['class'=>'btn1 load-more','style' => 'width:100%;']) !!}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
        </div>
    </div>
@endsection
