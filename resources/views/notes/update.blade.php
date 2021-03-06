@extends('layouts.app')

@section('content')
    <div class="row entry-form-full">
        <div class="col-xs-12">
            <h2 class="h2-new">
                <i class="bs-note cu-gift2" style="margin-right: 7px;"></i>
                Edit Note
            </h2>
            @include('reader.my-study-verse-btn')
        </div>
    </div>
    <div class="c-white-content create-item">
        <div class="entry-full-page">
            <div class="row">
                <div class="col-xs-12">
                    @include('notes.form')
                </div>
            </div>
        </div>
    </div>
@endsection