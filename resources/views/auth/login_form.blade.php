<form class="form-horizontal" role="form" method="POST" action="{{ url('auth/login') }}">
    {!! csrf_field() !!}

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label class="col-md-3 control-label">E-Mail</label>

        <div class="col-md-8">
            <input type="email" class="form-control input1" name="email" value="{{ old('email') }}">

            @if ($errors->has('email'))
                <span class="help-block">
                                        {{ $errors->first('email') }}
                                    </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <label class="col-md-3 control-label">Password</label>

        <div class="col-md-8">
            <input type="password" class="form-control input1" name="password">

            @if ($errors->has('password'))
                <span class="help-block">
                                        {{ $errors->first('password') }}
                                    </span>
            @endif
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-8 col-md-offset-3">
            <div class="checkbox">
                <input class="cust-radio" type="checkbox" name="remember" id="remember">
                <label for="remember" class="label-checkbox cu-label mt15">Remember Me</label>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-8 col-md-offset-3">
            <button type="submit" class="btn2-kit mt16 pull-left cu-btn-pad1">
                Login
            </button>
            <a class="forgot-link pull-right" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
        </div>
    </div>
</form>