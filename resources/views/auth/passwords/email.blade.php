@extends('layouts.app')

<!-- Main Content -->
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <h2 class="h2-new mb3">
                Reset Password
            </h2>
        </div>
    </div>
    <div class="c-white-content mb2" style="min-height: 577px">
        <div class="inner-pad5">
            <div class="row">
                <div class="col-xs-6 col-xs-offset-3">

                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                        {!! csrf_field() !!}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-xs-3 control-label">E-Mail Address</label>

                            <div class="col-xs-8">
                                <input type="email" class="form-control input1" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        {{ $errors->first('email') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-xs-8 col-md-offset-3">
                                <button type="submit" class="btn2-kit mt16 pull-left cu-btn-pad1">
                                    Send Password Reset Link
                                </button>
                            </div>
                        </div>
                    </form>
            </div>
        </div>
    </div>
</div>
@endsection
