@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <h2 class="h2-new mb3">
                <i class="bs-journal cu-gift2" style="margin-right: 7px;"></i>
                Create Journal Entry
            </h2>
        </div>
    </div>
    <div class="c-white-content create-item">
        <div class="entry-full-page">
            <div class="row">
                <div class="col-xs-12">
                    @include('journal.form')
                </div>
            </div>
        </div>
    </div>
@endsection