@extends('layouts.app')

@section('content')
    <div class="row groups-list j-groups-list">
        <div class="col-md-3">
            @include('community.menu')
        </div>
        <div class="col-md-9">
            <div class="c-white-content mb2">
                <div class="inner-pad2">
                    <h4 class="h4-kit" style="font-size: 18px; line-height: 22px"><i class="bs-edit cu-add"></i>Edit Group</h4>
                    @include('groups.form')
                </div>
            </div>
        </div>
    </div>
@endsection