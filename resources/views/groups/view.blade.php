@extends('layouts.app')

@section('content')
    <div class="row wall">
        <div class="col-md-2">
            @include('community.menu')
        </div>
        <div class="col-md-10 related-records public-wall">
            @role('user')
            <ul class="nav nav-pills wall-nav">
                <li role="presentation" class="{!! !Request::get('p')?'active':'' !!}">
                    <a href="{!! url('/groups/view/'.$model->id) !!}">Feed</a>
                </li>
                <li role="presentation" class="{!! (Request::get('p') == 'members')?'active':'' !!}">
                    <a href="{!! url('/groups/view/'.$model->id.'?p=members') !!}">Members</a>
                </li>
            </ul>
            @endrole
            @include('groups.wall-items')
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
