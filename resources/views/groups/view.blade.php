@extends('layouts.app')

@section('content')
    <div class="row wall">
        <div class="col-md-2">
            @include('community.menu')
        </div>
        <div class="col-md-10">
            <div class="group-details" style="background: url('{!!  $model->group_image?Config::get('app.groupImages').$model->id.'/'.$model->group_image:'/images/nocatimg-m.jpg' !!}'); {!! $model->group_image?'background-size: 100%;':'' !!} background-position: center center;">
                <div class="header" style="">
                    <div class="col-md-6 title">
                        {!! $model->group_name !!}
                    </div>
                    <div class="col-md-6 controls">
                        @if((Auth::user() && $model->owner_id == Auth::user()->id))
                            <a title="Delete group" href="{!! url('/groups/delete/'.$model->id,[],false) !!}" class="pull-right j-remove-group" data-toggle="modal"
                               data-target="#confirm-delete" data-header="Delete Confirmation"
                               data-confirm="Are you sure you want to delete this item?"><i class="fa fa-btn fa-trash" style="font-size: 18px;"></i></a>
                            <a href="{!! url('/groups/update/'.$model->id,[],false) !!}" class="pull-right"><i class="fa fa-btn fa-cog" style="font-size: 18px;"></i></a>
                        @else
                            <div class="pull-right" title="{!! Auth::user() && Auth::user()->isPremium() || in_array($model->id,$content['joinedGroupsKeys'])?'':'Premium Feature' !!}">
                                <a href="{!! url('/groups/leave-group/'.$model->id,[],false) !!}" class="pull-right btn btn-danger j-leave-group {!! in_array($model->id,$content['joinedGroupsKeys'])?'':'hidden' !!}"><i class="fa fa-btn fa-minus" style="font-size: 14px;"></i>Leave Group</a>
                                <a href="{!! url('/groups/join-group/'.$model->id,[],false) !!}" class="pull-right btn btn-primary j-join-group {!! in_array($model->id,$content['joinedGroupsKeys'])?'hidden':'' !!} {!! Auth::user() && Auth::user()->isPremium()?'':'disabled' !!}"><i class="fa fa-btn fa-plus" style="font-size: 14px;"></i>Join Group</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @role('user')
            <div>
                <ul class="nav nav-pills wall-nav">
                    <li role="presentation" class="{!! !Request::get('p')?'active':'' !!}">
                        <a href="{!! url('/groups/view/'.$model->id) !!}">Feed</a>
                    </li>
                    <li role="presentation" class="{!! (Request::get('p') == 'members')?'active':'' !!}">
                        <a href="{!! url('/groups/view/'.$model->id.'?p=members') !!}">Members</a>
                    </li>
                </ul>
            </div>
            @endrole
            <div class="row">
                <div class="col-md-9 related-records public-wall">
                    @include('groups.wall-items')
                </div>
                <div class="col-md-3 group-info" style="border-left: 1px solid #ccc">
                    <div class="clearfix info-block">
                        <div>
                            <div class="pull-left"><strong>Members</strong></div>
                            <div class="pull-right">
                                <a href="{!! url('/groups/view/'.$model->id.'?p=members') !!}">
                                {!! $model->members->count()+1 !!} member{!! $model->members->count()+1 == 1?'':'s' !!}
                                </a>
                            </div>
                        </div>
                        <div></div>
                    </div>
                    @if((Auth::user() && $model->owner_id == Auth::user()->id))
                    <div class="clearfix info-block">
                        <div><strong>Request Members</strong></div>
                        <div>
                            {!! Form::select('tags[]', ['user 1','user 2'], []/*$model->tags->pluck('id')->toArray()*/,['placeholder' => 'Enter user name...','multiple' => true,'class' => 'clear-fix j-select2', 'style' => '']) !!}
                        </div>
                    </div>
                    @endif
                    <div class="clearfix info-block">
                        <div><strong>Description</strong></div>
                        <div>{!! nl2br($model->group_desc) !!}</div>
                    </div>
                </div>
            </div>

        </div>
        {{--<div class="col-md-3">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Filters</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            @include('community.wall-filters')
                        </div>
                    </div>
                </div>
            </div>
        </div>--}}
    </div>
@endsection
