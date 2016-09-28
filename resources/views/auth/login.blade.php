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
                <div class="col-xs-6 col-xs-offset-3">

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('auth/login') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-xs-3 control-label">E-Mail</label>

                            <div class="col-xs-8">
                                <input type="email" class="form-control input1" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        {{ $errors->first('email') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-xs-3 control-label">Password</label>

                            <div class="col-xs-8">
                                <input type="password" class="form-control input1" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        {{ $errors->first('password') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-8 col-xs-offset-3">
                                <div class="checkbox">
                                    <input class="cust-radio" type="checkbox" name="remember" id="remember">
                                    <label for="remember" class="label-checkbox cu-label mt15">Remember Me</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-8 col-xs-offset-3">
                                <button type="submit" class="btn2-kit mt16 pull-left cu-btn-pad1">
                                    Login
                                </button>
                                <a class="forgot-link pull-right" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


@endsection
