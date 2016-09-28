@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <h2 class="h2-new mb3">
                <i class="bs-pray cu-gift2" style="margin-right: 7px;"></i>
                Edit Prayer
            </h2>
        </div>
    </div>
    <div class="c-white-content">
        <div class="entry-full-page">
            <div class="row">
                <div class="col-xs-12">
                    @include('prayers.form')
                </div>
            </div>
        </div>
    </div>
@endsection