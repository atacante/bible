@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center">My Prayers</h3>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Create Prayer</div>
        <div class="panel-body">
            @include('prayers.form')
        </div>
    </div>
@endsection