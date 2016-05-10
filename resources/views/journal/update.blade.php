@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h3 class="text-center">My Journal</h3>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">Edit Journal Entry</div>
        <div class="panel-body">
            @include('journal.form')
        </div>
    </div>
@endsection