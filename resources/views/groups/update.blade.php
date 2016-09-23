@extends('layouts.app')

@section('content')
    <div class="row groups-list j-groups-list">
        <div class="col-md-3">
            @include('community.menu')
        </div>
        <div class="col-xs-9">
            <div class="panel panel-default">
                <div class="panel-heading">Edit Group</div>
                <div class="panel-body">
                    @include('groups.form')
                </div>
            </div>
        </div>
    </div>
@endsection