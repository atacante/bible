@extends('layouts.app')
{{-- Web site Title --}}
@section('title')
    @parent
@stop

@section('content')
    <div class="row groups-list j-groups-list">
        <div class="col-md-3">
            @include('community.menu')
        </div>
        <div class="col-md-9">
            @role('user')
            <div class="resp-text-center">
                <ul class="nav nav-pills tabs-nav">
                    <li role="presentation" class="{!! (!Request::get('type') || Request::get('type') == 'all')?'active':'' !!}">
                        <a href="{!! url('/groups?'.http_build_query(array_merge(Request::input(),['type' => 'all']))) !!}">Discover</a>
                    </li>
                    <li role="presentation" class="{!! (Request::get('type') == 'my')?'active':'' !!}">
                        <a href="{!! url('/groups?'.http_build_query(array_merge(Request::input(),['type' => 'my']))) !!}">My Groups</a>
                    </li>
                </ul>
            </div>
            @endrole

            @if(Request::get('type') == 'my' && Auth::user())
                @include('groups.my-list')
            @else
                @include('groups.discover-list')
            @endif
        </div>
    </div>
@stop
