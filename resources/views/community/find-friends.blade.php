@extends('layouts.app')

@section('content')
    {{--<h3 class="text-center">Find Friends</h3>--}}
    <div class="row friends-list j-friends-list">
        <div class="col-md-3">
            @include('community.menu')
        </div>
        <div class="col-md-9">
            <div class="clearfix">
                @role('user')
                <ul class="nav nav-pills tabs-nav">
                    <li role="presentation" class="{!! (!Request::get('type') || Request::get('type') == 'all')?'active':'' !!}">
                        <a href="{!! url('/community/find-friends?'.http_build_query(array_merge(Request::input(),['type' => 'all']))) !!}">All users</a>
                    </li>
                    <li role="presentation" class="{!! (Request::get('type') == 'my')?'active':'' !!}">
                        <a href="{!! url('/community/find-friends?'.http_build_query(array_merge(Request::input(),['type' => 'my']))) !!}">My friends</a>
                    </li>
                    <li role="presentation" class="{!! (Request::get('type') == 'new')?'active':'' !!}">
                        <a href="{!! url('/community/find-friends?'.http_build_query(array_merge(Request::input(),['type' => 'new']))) !!}">New users</a>
                    </li>
                    <li role="presentation" class="{!! (Request::get('type') == 'inbox-requests' || Request::get('type') == 'sent-requests')?'active':'' !!}">
                        <a href="{!! url('/community/find-friends?'.http_build_query(array_merge(Request::input(),['type' => 'inbox-requests']))) !!}">Friend Requests</a>
                    </li>
                    <li class="pull-right">
                        @include('community.friends-filters')
                    </li>
                </ul>
                @endrole
            </div>
            <div class="j-friends-items">
                <div class="row">
                    <div class="col-md-12">
                        <div class="">
                            <div class="u-header">
                                <div class="">
                                    <strong>
                                        @if(!Request::get('type') || Request::get('type') == 'all')
                                            {{--All Users--}}
                                        @elseif(Request::get('type') == 'my')
                                            {{--My Friends--}}
                                        @elseif(Request::get('type') == 'new')
                                            {{--New Users--}}
                                        @elseif(Request::get('type') == 'inbox-requests' || Request::get('type') == 'sent-requests')
                                            <ul class="nav nav-pills">
                                                <li role="presentation" class="{!! (Request::get('type') == 'inbox-requests')?'active':'' !!}"><a href="{!! url('/community/find-friends?'.http_build_query(array_merge(Request::input(),['type' => 'inbox-requests']))) !!}">Inbox Requests</a></li>
                                                <li role="presentation" class="{!! (Request::get('type') == 'sent-requests')?'active':'' !!}"><a href="{!! url('/community/find-friends?'.http_build_query(array_merge(Request::input(),['type' => 'sent-requests']))) !!}">Sent Requests</a></li>
                                            </ul>
                                        @endif
                                    </strong>
                                </div>
                            </div>
                            <div class="row cu1-row">
                                @include('community.friend-items')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
