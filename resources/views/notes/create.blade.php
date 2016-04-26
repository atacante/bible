@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center">My Notes</h3>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Create Note</div>
        <div class="panel-body">
            @include('notes.form')
        </div>
    </div>
@endsection