@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <h2 class="h2-new mb3">
                <i class="bs-login cu-login"></i>
                LOGIN
            </h2>
        </div>
    </div>
    <div class="c-white-content mb2" style="min-height: 577px">
        <div class="inner-pad5">
            <div class="row">
                <div class="col-xs-12 col-lg-6 col-lg-offset-3">
                    @include("auth.login_form")
                </div>
            </div>
        </div>
    </div>
@endsection
