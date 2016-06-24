@extends('layouts.app')

@section('content')
    <div class="row groups-list j-groups-list">
        <div class="col-md-2">
            @include('community.menu')
        </div>
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">Group Settings</div>
                <div class="panel-body">
                    @include('groups.form')
                </div>
            </div>
        </div>
    </div>
@endsection