<div class="box-body">
    <form class="" role="form" id="login-form" method="POST" action="{{ url('auth/login') }}">
        {!! csrf_field() !!}

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label class="control-label">E-Mail</label>
            <input type="email" class="form-control input1" name="email" value="{{ old('email') }}">

            @if ($errors->has('email'))
                <span class="help-block">
                    {{ $errors->first('email') }}
                </span>
            @endif
        </div>

        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label class="control-label">Password</label>
            <input type="password" class="form-control input1" name="password">

            @if ($errors->has('password'))
                <span class="help-block">
                    {{ $errors->first('password') }}
                </span>
            @endif
        </div>

        <div class="form-group">
            <div class="checkbox">
                <input class="cust-radio" type="checkbox" name="remember" id="remember">
                <label for="remember" class="label-checkbox cu-label mt15">Remember Me</label>
            </div>
        </div>

        <div class="form-group clearfix">
            <button type="submit" class="j-login btn2-kit mt16 pull-left cu-btn-pad1">
                Login
            </button>
            <a class="forgot-link pull-right" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
        </div>
    </form>
</div>