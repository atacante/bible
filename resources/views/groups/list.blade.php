@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    <div class="row groups-list j-groups-list">
        <div class="col-md-2">
            @include('community.menu')
        </div>
        <div class="col-md-10" style="line-height: 30px;">
            @role('user')
            <ul class="nav nav-pills friends-nav">
                <li role="presentation" class="{!! (!Request::get('type') || Request::get('type') == 'all')?'active':'' !!}">
                    <a href="{!! url('/groups?'.http_build_query(array_merge(Request::input(),['type' => 'all']))) !!}">Discover</a>
                </li>
                <li role="presentation" class="{!! (Request::get('type') == 'my')?'active':'' !!}">
                    <a href="{!! url('/groups?'.http_build_query(array_merge(Request::input(),['type' => 'my']))) !!}">My Groups</a>
                </li>
            </ul>
            @endrole
            @if(Request::get('type') == 'my' && Auth::user())
                @include('groups.my-list')
            @else
                @include('groups.discover-list')
            @endif
        </div>
        {{--<div class="col-md-3">
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
        </div>--}}
    </div>
@stop
